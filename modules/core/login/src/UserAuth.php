<?php

namespace Drupal\farm_login;

use Drupal\user\UserAuth as CoreUserAuth;

/**
 * Extends the core user.auth service to load users by their email.
 */
class UserAuth extends CoreUserAuth {

  /**
   * {@inheritdoc}
   */
  public function authenticate($username, $password) {
    $uid = parent::authenticate($username, $password);

    // If the parent failed to authenticate, try loading the user by email.
    if (empty($uid) && !empty($username) && strlen($password) > 0) {
      $account_search = $this->entityTypeManager->getStorage('user')->loadByProperties(['mail' => $username]);

      if ($account = reset($account_search)) {
        if ($this->passwordChecker->check($password, $account->getPassword())) {
          // Successful authentication.
          $uid = $account->id();

          // Update user to new password scheme if needed.
          if ($this->passwordChecker->needsRehash($account->getPassword())) {
            $account->setPassword($password);
            $account->save();
          }
        }
      }
    }

    return $uid;
  }

}
