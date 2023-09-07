<?php

namespace Drupal\farm_ui_theme\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderCallbackInterface;

/**
 * Log form for gin content form.
 */
class LogForm extends GinContentFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getFieldGroups() {
    return parent::getFieldGroups() + [
      'quantity' => [
        'location' => 'main',
        'title' => $this->t('Quantity'),
        'weight' => 50,
      ],
    ];
  }

}
