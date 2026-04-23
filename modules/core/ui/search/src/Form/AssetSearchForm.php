<?php

declare(strict_types=1);

namespace Drupal\farm_ui_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Asset search form.
 */
class AssetSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_ui_search_asset_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Asset search textfield.
    $form['asset_search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Asset search'),
      '#title_display' => 'invisible',
    ];

    // Search submit button.
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Redirect to the farm_asset View page display with the name exposed filter
    // pre-populated.
    $form_state->setRedirect('view.farm_asset.page', [], ['query' => ['name' => $form_state->getValue('asset_search')]]);
  }

}
