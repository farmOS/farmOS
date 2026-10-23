<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use Drupal\Core\Site\Settings;

/**
 * Pins a service address. Public https is the default.
 *
 * A local farmOS site may set farm_oauth_client_allow_local_http in
 * settings.php to allow http only for loopback and host.docker.internal.
 */
final class PublicHttps {

  /**
   * @return array{url: string, resolve: string}
   */
  public static function pin(string $url): array {
    $parts = self::parts($url);
    $host = strtolower((string) $parts['host']);
    if (self::allowLocal() && self::isLocalHost($host)) {
      return self::pinned($parts, $host, self::localAddress($host));
    }
    if (($parts['scheme'] ?? '') !== 'https') {
      throw new \InvalidArgumentException('The service URL must be https.');
    }
    if ($host === 'localhost' || str_ends_with($host, '.local') || str_ends_with($host, '.internal')) {
      throw new \InvalidArgumentException('The service host must be public.');
    }
    $ip = filter_var($host, FILTER_VALIDATE_IP) ? $host : self::firstPublicAddress($host);
    $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
    if (!filter_var($ip, FILTER_VALIDATE_IP, $flags)) {
      throw new \InvalidArgumentException('The service host must be a public address.');
    }
    return self::pinned($parts, $host, $ip);
  }

  private static function allowLocal(): bool {
    return Settings::get('farm_oauth_client_allow_local_http') === TRUE;
  }

  private static function isLocalHost(string $host): bool {
    return in_array($host, ['localhost', '127.0.0.1', 'host.docker.internal'], TRUE);
  }

  private static function localAddress(string $host): string {
    $gateway = gethostbyname('host.docker.internal');
    $gateway_ok = filter_var($gateway, FILTER_VALIDATE_IP) && $gateway !== 'host.docker.internal' && !str_starts_with($gateway, '127.');
    if ($gateway_ok && ($host === '127.0.0.1' || $host === 'localhost')) {
      return $gateway;
    }
    if (filter_var($host, FILTER_VALIDATE_IP)) {
      return $host;
    }
    $ip = gethostbyname($host);
    if (!filter_var($ip, FILTER_VALIDATE_IP) || $ip === $host) {
      throw new \InvalidArgumentException('The local service host did not resolve.');
    }
    return $ip;
  }

  /**
   * @return array{url: string, resolve: string}
   */
  private static function pinned(array $parts, string $host, string $ip): array {
    $scheme = (string) $parts['scheme'];
    $port = isset($parts['port']) ? (int) $parts['port'] : ($scheme === 'https' ? 443 : 80);
    if ($port < 1 || $port > 65535) {
      throw new \InvalidArgumentException('The service URL has a bad port.');
    }
    $path = isset($parts['path']) ? rtrim((string) $parts['path'], '/') : '';
    $suffix = ($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80) ? '' : ':' . $port;
    $address = str_contains($ip, ':') ? '[' . $ip . ']' : $ip;
    return [
      'url' => $scheme . '://' . $host . $suffix . $path,
      'resolve' => $host . ':' . $port . ':' . $address,
    ];
  }

  private static function parts(string $url): array {
    $parts = parse_url(trim($url));
    if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
      throw new \InvalidArgumentException('The service URL must include a host.');
    }
    if (!empty($parts['user']) || !empty($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
      throw new \InvalidArgumentException('The service URL cannot include credentials or a query.');
    }
    return $parts;
  }

  private static function firstPublicAddress(string $host): string {
    $records = @dns_get_record($host, DNS_A | DNS_AAAA);
    $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
    if (is_array($records)) {
      foreach ($records as $record) {
        $ip = $record['ip'] ?? $record['ipv6'] ?? '';
        if (is_string($ip) && filter_var($ip, FILTER_VALIDATE_IP, $flags)) {
          return $ip;
        }
      }
    }
    throw new \InvalidArgumentException('The service host must be a public address.');
  }

}
