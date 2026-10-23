<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Drupal\farm_oauth_client\OAuthFlow;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Starts sign-in and receives the authorization code.
 */
final class FlowController implements ContainerInjectionInterface {

  public function __construct(
    private OAuthFlow $flow,
    private MessengerInterface $messenger,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('farm_oauth_client.flow'),
      $container->get('messenger'),
    );
  }

  public function connect(string $provider): RedirectResponse {
    try {
      $response = new TrustedRedirectResponse($this->flow->authorizeUrl($provider));
      $response->getCacheableMetadata()->setCacheMaxAge(0);
      return $response;
    }
    catch (\Throwable $e) {
      $this->messenger->addError($e->getMessage());
      return $this->home($provider);
    }
  }

  public function callback(string $provider, Request $request): RedirectResponse {
    try {
      $this->flow->finish($provider, $request);
      $this->messenger->addStatus('This farm is signed in.');
    }
    catch (\Throwable $e) {
      $this->messenger->addError($e->getMessage());
    }
    return $this->home($provider);
  }

  private function home(string $provider): RedirectResponse {
    try {
      if ($provider === 'piknik') {
        return new RedirectResponse(Url::fromRoute('farm_piknik.settings')->toString());
      }
    }
    catch (\Exception) {
    }
    return new RedirectResponse(Url::fromRoute('<front>')->toString());
  }

}
