<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use Drupal\Core\Site\Settings;

/**
 * Seals client secrets and refresh tokens. The key stays in settings.php.
 */
final class SecretBox {

  public function canSeal(): bool {
    try {
      $this->key();
      return TRUE;
    }
    catch (\RuntimeException) {
      return FALSE;
    }
  }

  public function seal(string $secret): string {
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    return base64_encode($nonce . sodium_crypto_secretbox($secret, $nonce, $this->key()));
  }

  public function open(string $stored): string {
    $raw = base64_decode($stored, TRUE);
    $nonce_len = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
    if ($raw === FALSE || strlen($raw) < $nonce_len + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
      throw new \RuntimeException('Stored secret is not usable.');
    }
    $open = sodium_crypto_secretbox_open(substr($raw, $nonce_len), substr($raw, 0, $nonce_len), $this->key());
    if ($open === FALSE) {
      throw new \RuntimeException('Stored secret could not be opened.');
    }
    return $open;
  }

  private function key(): string {
    $encoded = Settings::get('farm_oauth_client_key');
    if (!is_string($encoded) || $encoded === '') {
      $encoded = Settings::get('farm_piknik_key');
    }
    if (!is_string($encoded) || $encoded === '') {
      throw new \RuntimeException('farm_oauth_client_key is not set in settings.php.');
    }
    $key = base64_decode($encoded, TRUE);
    if ($key === FALSE || strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
      throw new \RuntimeException('farm_oauth_client_key must be 32 bytes, base64-encoded.');
    }
    return $key;
  }

}
