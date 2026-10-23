<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use Drupal\Core\Url;

/**
 * OAuth callback on this farm's own address.
 *
 * farmOS publishes that address as $base_url, including meta.farm.url.
 */
final class SiteCallback {

  public static function url(string $provider): string {
    if (!preg_match('/^[a-z]{3,32}$/', $provider)) {
      throw new \InvalidArgumentException('Unknown service.');
    }
    return Url::fromRoute('farm_oauth_client.callback', ['provider' => $provider], ['absolute' => TRUE])->toString();
  }

}
