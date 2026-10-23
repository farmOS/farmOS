<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Place select shown only after Piknik sign-in.
 */
final class PlaceField {

  use StringTranslationTrait;

  public function build(PlaceDirectory $places, StateInterface $state, MessengerInterface $messenger): array {
    try {
      $choices = $places->choices();
    }
    catch (\Throwable $e) {
      $messenger->addWarning($e->getMessage());
      $choices = [];
    }
    $saved = $state->get('farm_piknik.participant_id', '');
    return [
      '#type' => 'select',
      '#title' => $this->t('Place'),
      '#options' => $choices,
      '#empty_option' => $this->t('- Select -'),
      '#default_value' => is_string($saved) && isset($choices[$saved]) ? $saved : NULL,
      '#description' => $this->t('Farm place used for the Piknik board, harvest posting, and new listings. Farms you manage only.'),
    ];
  }

}
