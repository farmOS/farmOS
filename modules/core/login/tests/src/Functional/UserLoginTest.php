<?php

namespace Drupal\Tests\farm_login\Functional;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\user\Entity\User;

/**
 * Test using an email in the UserLoginForm.
 *
 * These tests are based on the core UserLoginTests.
 *
 * @see \Drupal\Tests\farm_login\Functional\UserLoginTest
 *
 * @group farm
 */
class UserLoginTest extends FarmBrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_login',
  ];

  /**
   * Tests login with destination.
   */
  public function testValidLoginWithDestination() {

    // 1. Test for correct text in the login form.
    $this->drupalGet('user/login');
    $this->assertSession()->pageTextContains($this->t('Email or username'));
    $this->assertSession()
      ->pageTextContains($this->t('Enter your @s email address or username.', [
        '@s' => $this->config('system.site')
          ->get('name'),
      ]));

    // 2. Login the user using their username.
    $user = $this->drupalCreateUser([]);
    $this->drupalGet('user/login', ['query' => ['destination' => 'foo']]);
    $edit = ['name' => $user->getAccountName(), 'pass' => $user->passRaw];
    $this->submitForm($edit, 'Log in');
    $this->assertSession()->addressEquals('foo');
    $this->drupalLogout();

    // 3. Login the user using their email.
    $user = $this->drupalCreateUser([]);
    $this->drupalGet('user/login', ['query' => ['destination' => 'foo']]);
    $edit = ['name' => $user->getEmail(), 'pass' => $user->passRaw];
    $this->submitForm($edit, 'Log in');
    $this->assertSession()->addressEquals('foo');
    $this->drupalLogout();

    // 4. Login with an invalid username/email.
    $user = $this->drupalCreateUser([]);
    $this->drupalGet('user/login', ['query' => ['destination' => 'foo']]);
    $edit = ['name' => 'invalid@email.com', 'pass' => $user->passRaw];
    $this->submitForm($edit, 'Log in');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldValueEquals('pass', '');
    $this->assertSession()->pageTextcontains('Unrecognized username or password. Forgot your password?');
  }

  /**
   * Test the per-user login flood control.
   *
   * A duplicate of the core test except login using username.
   *
   * It is important to test this since we are altering the UserLoginForm,
   * which could potentially skip the flood validation.
   *
   * @see UserLoginTest::testPerUserLoginFloodControl()
   */
  public function testPerUserLoginFloodControl() {
    $this->config('user.flood')
      // Set a high global limit out so that it is not relevant in the test.
      ->set('ip_limit', 4000)
      ->set('user_limit', 3)
      ->save();

    $user1 = $this->drupalCreateUser([]);
    $incorrect_user1 = clone $user1;
    $incorrect_user1->passRaw .= 'incorrect';

    $user2 = $this->drupalCreateUser([]);

    // Try 2 failed logins.
    for ($i = 0; $i < 2; $i++) {
      $this->assertFailedLoginUsingEmail($incorrect_user1);
    }

    // A successful login will reset the per-user flood control count.
    $this->drupalLoginUsingEmail($user1);
    $this->drupalLogout();

    // Try 3 failed logins for user 1, they will not trigger flood control.
    for ($i = 0; $i < 3; $i++) {
      $this->assertFailedLoginUsingEmail($incorrect_user1);
    }

    // Try one successful attempt for user 2, it should not trigger any
    // flood control.
    $this->drupalLoginUsingEmail($user2);
    $this->drupalLogout();

    // Try one more attempt for user 1, it should be rejected, even if the
    // correct password has been used.
    $this->assertFailedLoginUsingEmail($user1, 'user');
  }

  /**
   * Make an unsuccessful login using the account email.
   *
   * A copy of the core assertFailedLogin() method, but that uses email instead.
   *
   * @param \Drupal\user\Entity\User $account
   *   A user object with name and passRaw attributes for the login attempt.
   * @param mixed $flood_trigger
   *   (optional) Whether or not to expect that the flood control mechanism
   *    will be triggered. Defaults to NULL.
   *   - Set to 'user' to expect a 'too many failed logins error.
   *   - Set to any value to expect an error for too many failed logins per IP
   *   .
   *   - Set to NULL to expect a failed login.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *
   * @see UserLoginTest::assertFailedLogin()
   */
  public function assertFailedLoginUsingEmail(User $account, $flood_trigger = NULL) {
    $database = \Drupal::database();
    $this->drupalGet(Url::fromRoute('user.login'));
    $this->submitForm([
      'name' => $account->getEmail(),
      'pass' => $account->passRaw,
    ], $this->t('Log in'));
    if (isset($flood_trigger)) {
      $this->assertSession()->statusCodeEquals(403);
      $this->assertSession()->fieldNotExists('pass');
      $last_log = $database->select('watchdog', 'w')
        ->fields('w', ['message'])
        ->condition('type', 'user')
        ->orderBy('wid', 'DESC')
        ->range(0, 1)
        ->execute()
        ->fetchField();
      if ($flood_trigger == 'user') {
        $this->assertSession()->responseContains(\Drupal::translation()->formatPlural($this->config('user.flood')->get('user_limit'), 'There has been more than one failed login attempt for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', 'There have been more than @count failed login attempts for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', [':url' => Url::fromRoute('user.pass')->toString()]));
        $this->assertEquals('Flood control blocked login attempt for uid %uid from %ip', $last_log, 'A watchdog message was logged for the login attempt blocked by flood control per user.');
      }
      else {
        // No uid, so the limit is IP-based.
        $this->assertSession()->responseContains($this->t('Too many failed login attempts from your IP address. This IP address is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', [':url' => Url::fromRoute('user.pass')->toString()]));
        $this->assertEquals('Flood control blocked login attempt from %ip', $last_log, 'A watchdog message was logged for the login attempt blocked by flood control per IP.');
      }
    }
    else {
      $this->assertSession()->statusCodeEquals(200);
      $this->assertSession()->fieldValueEquals('pass', '');
      $this->assertSession()->pageTextContains('Unrecognized username or password. Forgot your password?');
    }
  }

  /**
   * A helper function to login using an email.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   User object representing the user to log in.
   *
   * @see drupalLogin()
   * @see drupalCreateUser()
   */
  protected function drupalLoginUsingEmail(AccountInterface $account) {
    if ($this->loggedInUser) {
      $this->drupalLogout();
    }

    $this->drupalGet(Url::fromRoute('user.login'));
    $this->submitForm([
      'name' => $account->getEmail(),
      'pass' => $account->passRaw,
    ], $this->t('Log in'));

    // @see ::drupalUserIsLoggedIn()
    $account->sessionId = $this->getSession()->getCookie(\Drupal::service('session_configuration')->getOptions(\Drupal::request())['name']);
    $this->assertTrue($this->drupalUserIsLoggedIn($account), new FormattableMarkup('User %name successfully logged in.', ['%name' => $account->getAccountName()]));

    $this->loggedInUser = $account;
    $this->container->get('current_user')->setAccount($account);
  }

}
