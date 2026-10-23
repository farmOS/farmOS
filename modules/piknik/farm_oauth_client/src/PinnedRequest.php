<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Calls one pinned https host and refuses redirects.
 */
final class PinnedRequest {

  public function __construct(
    private ClientInterface $httpClient,
  ) {}

  public function send(string $base, string $method, string $path, array $options): ResponseInterface {
    if (!str_starts_with($path, '/') || str_starts_with($path, '//')) {
      throw new \InvalidArgumentException('The request path must stay on the saved host.');
    }
    if (!extension_loaded('curl') || !defined('CURLOPT_RESOLVE')) {
      throw new \RuntimeException('The request needs curl so the address can be pinned.');
    }
    $pin = PublicHttps::pin($base);
    $options['timeout'] = 10;
    $options['allow_redirects'] = FALSE;
    $options['http_errors'] = FALSE;
    $options['curl'] = [CURLOPT_RESOLVE => [$pin['resolve']]];
    return $this->httpClient->request($method, $pin['url'] . $path, $options);
  }

}
