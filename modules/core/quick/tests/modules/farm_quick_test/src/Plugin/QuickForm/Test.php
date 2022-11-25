<?php

namespace Drupal\farm_quick_test\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickAssetTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickQuantityTrait;
use Drupal\farm_quick\Traits\QuickTermTrait;

/**
 * Test quick form.
 *
 * @QuickForm(
 *   id = "test",
 *   label = @Translation("Test quick form"),
 *   description = @Translation("Test quick form description."),
 *   helpText = @Translation("Test quick form help text."),
 *   permissions = {
 *     "create test log",
 *   }
 * )
 */
class Test extends QuickFormBase {

  use QuickAssetTrait;
  use QuickLogTrait;
  use QuickQuantityTrait;
  use QuickTermTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Test field.
    $form['test'] = [
      '#type' => 'number',
      '#title' => $this->t('Test field'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the submitted value.
    $value = $form_state->getValue('test');

    // Create an asset.
    $asset = $this->createAsset([
      'type' => 'test',
      'name' => $value,
    ]);

    // Create a log.
    $log = $this->createLog([
      'type' => 'test',
      'name' => $value,
      'quantity' => [
        [
          'measure' => 'count',
          'value' => $value,
          'units' => 'tests',
        ],
      ],
    ]);

    // Create a quantity.
    $quantity = $this->createQuantity([
      'measure' => 'count',
      'value' => $value,
      'units' => 'tests',
      'label' => $this->t('test label'),
      'type' => 'test2',
    ]);

    // Create a term.
    $term1 = $this->createTerm([
      'name' => 'test1',
      'vocabulary' => 'test',
    ]);

    // Create a term with createOrLoadTerm().
    $term2 = $this->createOrLoadTerm('test2', 'test');

    // Load a term with createOrLoadTerm().
    $term3 = $this->createOrLoadTerm('test2', 'test');

    // Save entities to form state for automated test review.
    $storage = [];
    $storage['assets'][] = $asset;
    $storage['logs'][] = $log;
    $storage['quantities'][] = $quantity;
    $storage['terms'][] = $term1;
    $storage['terms'][] = $term2;
    $storage['terms'][] = $term3;
    $form_state->setStorage($storage);
  }

}
