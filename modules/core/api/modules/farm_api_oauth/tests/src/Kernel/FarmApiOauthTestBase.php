<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_api_oauth\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\Tests\farm_api\Kernel\FarmApiTest;
use Drupal\Tests\simple_oauth\Functional\SimpleOauthTestTrait;
use Drupal\consumers\Entity\Consumer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests farmOS API OAuth features.
 */
abstract class FarmApiOauthTestBase extends FarmApiTest {

  use SimpleOauthTestTrait;

  /**
   * The client.
   *
   * @var \Drupal\consumers\Entity\Consumer
   */
  protected $client;

  /**
   * The client secret.
   *
   * @var string
   */
  protected $clientSecret;

  /**
   * The scope.
   *
   * @var string
   */
  protected $scope;

  /**
   * The URL.
   *
   * @var \Drupal\Core\Url
   */
  protected $url;

  /**
   * The user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'consumers',
    'farm_api_oauth',
    'simple_oauth',
    'simple_oauth_password_grant',
    'simple_oauth_static_scope',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('consumer');
    $this->installEntitySchema('oauth2_token');
    $this->installEntitySchema('user');
    $this->installConfig([
      'simple_oauth',
      'simple_oauth_password_grant',
      'user',
    ]);

    // Set up the private filesystem path.
    $directory = 'public://private';
    mkdir($directory, 0775);
    $this->assertDirectoryIsWritable($directory);
    $this->setSetting('file_private_path', Settings::get('file_public_path') . '/private');

    // Configure simple_oauth keys.
    $this->setUpKeys();

    // Configure site to use static scopes.
    $this->config('simple_oauth.settings')->set('scope_provider', 'static')->save();

    // Configure client with password grant and no default scopes.
    $this->url = Url::fromRoute('oauth2_token.token');
    $this->clientSecret = $this->randomString();
    $this->client = Consumer::create([
      'client_id' => 'test_client',
      'is_default' => TRUE,
      'label' => 'test',
      'grant_types' => [
        'password',
      ],
      'scopes' => [],
      'secret' => $this->clientSecret,
    ]);
    $this->client->save();

    // Create a new user to ensure we are not using user id 1.
    $this->user = $this->createUser();
    $this->assertNotEquals(1, $this->user->id());
    $this->setCurrentUser($this->user);

    // Test using the farm_manager role.
    $this->scope = 'farm_manager';
    $this->user->addRole('farm_manager');
    $this->user->save();
  }

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    parent::register($container);

    // Register the private:// stream wrapper.
    $container->register('stream_wrapper.private', 'Drupal\Core\StreamWrapper\PrivateStream')
      ->addTag('stream_wrapper', ['scheme' => 'private']);
  }

  /**
   * {@inheritdoc}
   */
  protected function assertApiRequest(string $endpoint, string $method = 'GET', array $payload = [], int|null $expected_response = NULL) {

    // This overrides and copies logic from the parent assertApiRequest()
    // function to add an Authorization header with an OAuth access token.
    // @see \Drupal\Tests\farm_api\Kernel\FarmApiTest::assertApiRequest
    $http_kernel = $this->container->get('http_kernel');
    $content = '';
    if (!empty($payload)) {
      $content = Json::encode([
        'data' => $payload,
      ]);
    }
    $request = Request::create($endpoint, $method, [], [], [], [], $content);
    $request->headers->set('Accept', 'application/vnd.api+json');
    $request->headers->set('Authorization', 'Bearer ' . $this->assertGetAccessToken());
    $request->headers->set('Content-Type', 'application/vnd.api+json');
    $response = $http_kernel->handle($request);
    if (is_null($expected_response)) {
      $expected_responses = [
        'GET' => Response::HTTP_OK,
        'POST' => Response::HTTP_CREATED,
        'PATCH' => Response::HTTP_OK,
        'DELETE' => Response::HTTP_NO_CONTENT,
      ];
      $expected_response  = $expected_responses[$method];
    }
    $this->assertEquals($expected_response, $response->getStatusCode());
    return Json::decode($response->getContent());
  }

  /**
   * Helper function to get an oauth2 access token for current user and scope.
   *
   * @return string
   *   Access token.
   */
  protected function assertGetAccessToken(): string {
    $parameters = [
      'grant_type' => 'password',
      'client_id' => $this->client->getClientId(),
      'client_secret' => $this->clientSecret,
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
      'scope' => $this->scope,
    ];

    // Make token request.
    $request = Request::create($this->url->toString(), 'POST', $parameters);
    $http_kernel = $this->container->get('http_kernel');
    $response = $http_kernel->handle($request);

    // Check for valid response.
    $this->assertEquals(200, $response->getStatusCode());
    $parsed_response = Json::decode((string) $response->getContent());
    $this->assertSame('Bearer', $parsed_response['token_type']);
    $this->assertNotEmpty($parsed_response['access_token']);
    return $parsed_response['access_token'];
  }

}
