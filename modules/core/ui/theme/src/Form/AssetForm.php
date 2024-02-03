<?php

namespace Drupal\farm_ui_theme\Form;

/**
 * Asset form for gin content form.
 */
class AssetForm extends GinContentFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getFieldGroups() {
    return parent::getFieldGroups() + [
      'parent' => [
        'location' => 'main',
        'title' => $this->t('Parents'),
        'weight' => 40,
      ],
      'id_tag' => [
        'location' => 'main',
        'title' => $this->t('ID Tags'),
        'weight' => 100,
      ],
    ];
  }

}
