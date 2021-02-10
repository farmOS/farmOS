<?php

namespace Drupal\farm_map\Form;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Provides a farm_map settings form.
 */
class MapSettingsForm extends ConfigFormbase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_map_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

}
