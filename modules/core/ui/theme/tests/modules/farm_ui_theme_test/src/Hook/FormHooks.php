<?php

declare(strict_types=1);

namespace Drupal\farm_ui_theme_test\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\State\StateInterface;

/**
 * Form fixtures for farmOS UI Theme tests.
 */
class FormHooks {

  public function __construct(protected StateInterface $state) {}

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_taxonomy_term_plant_type_form_alter')]
  public function formTaxonomyTermPlantTypeFormAlter(array &$form): void {
    $field = $this->state->get('farm_ui_theme_test.reference_field');
    if ($field && isset($form[$field])) {
      $form[$field]['#group'] = 'reference_field_group';
    }
  }

}
