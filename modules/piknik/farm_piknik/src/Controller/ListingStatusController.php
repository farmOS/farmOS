<?php

declare(strict_types=1);

namespace Drupal\farm_piknik\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\farm_piknik\ListingStatusApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Toggles a listing between active and cancelled (inactive) on Piknik.
 */
final class ListingStatusController extends ControllerBase {

  public function __construct(
    private ListingStatusApi $status,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('farm_piknik.listing_status'));
  }

  public function toggle(string $listing_id): RedirectResponse {
    $board = Url::fromRoute('farm_piknik.board')->toString();
    try {
      $next = $this->status->toggle($listing_id);
      $this->messenger()->addStatus($next === 'active'
        ? $this->t('Listing set to active.')
        : $this->t('Listing set to inactive.'));
    }
    catch (\Throwable $e) {
      $this->messenger()->addError($e->getMessage());
    }
    return new RedirectResponse($board);
  }

}
