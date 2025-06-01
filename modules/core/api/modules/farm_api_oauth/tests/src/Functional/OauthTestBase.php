<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_api_oauth\Functional;

use Drupal\Tests\simple_oauth\Functional\TokenBearerFunctionalTestBase;

/**
 * Base class that handles common logic for OAuth tests.
 *
 * @group farm
 */
class OauthTestBase extends TokenBearerFunctionalTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'image',
    'node',
    'serialization',
    'text',
    'user',
    'farm_api_oauth',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {

    parent::setUp();

    // Add a client_id to the client.
    $this->client->set('client_id', 'farm_test');
    $this->client->set('confidential', FALSE);
    $this->client->save();
  }

}
