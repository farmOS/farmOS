<?php

namespace Drupal\farm_ui_theme\Form;

/**
 * Log form for gin content form.
 */
class LogForm extends GinContentFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getFieldGroups() {
    return parent::getFieldGroups() + [
      'asset' => [
        'location' => 'main',
        'title' => $this->t('Assets'),
        'weight' => 40,
      ],
      'quantity' => [
        'location' => 'main',
        'title' => $this->t('Quantity'),
        'weight' => 100,
      ],
    ];
  }

}
