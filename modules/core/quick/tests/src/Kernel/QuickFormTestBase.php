<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_quick\Kernel;

use Drupal\Core\Form\FormState;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Base class that modules can use to test their quick forms.
 *
 * @internal
 */
abstract class QuickFormTestBase extends KernelTestBase {

  use UserCreationTrait;

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity',
    'entity_reference_revisions',
    'farm_entity',
    'farm_entity_access',
    'farm_entity_fields',
    'farm_field',
    'farm_form',
    'farm_format',
    'farm_geo',
    'farm_location',
    'farm_log',
    'farm_log_asset',
    'farm_log_quantity',
    'farm_map',
    'farm_quick',
    'file',
    'filter',
    'fraction',
    'geofield',
    'image',
    'log',
    'options',
    'quantity',
    'rest',
    'serialization',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
    'views',
    'views_geojson',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setUpCurrentUser([], [], TRUE);
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('quantity');
    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installConfig([
      'farm_format',
      'farm_location',
      'farm_log_asset',
      'system',
    ]);
  }

  /**
   * Helper function for performing a quick form submission.
   *
   * @param array $values
   *   The values to submit.
   */
  protected function submitQuickForm(array $values = []) {
    $form_arg = '\Drupal\farm_quick\Form\QuickForm';
    $form_state = (new FormState())->setValues($values);
    \Drupal::formBuilder()->submitForm($form_arg, $form_state, $this->quickFormId);
  }

}
