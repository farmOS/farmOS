<?php

declare(strict_types=1);

namespace Drupal\farm_ui_theme\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\farm_ui_theme\FarmUiThemeHelper;

/**
 * Form hook implementations for farm_ui_theme.
 */
class FormHooks {

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(&$form, FormStateInterface $form_state, $form_id) {
    $form['#attached']['library'][] = 'farm_ui_theme/form';
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_asset_form_alter')]
  public function formAssetFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    /** @var \Drupal\Core\Entity\EntityForm $form_object */
    $form_object = $form_state->getFormObject();
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $entity = $form_object->getEntity();
    FarmUiThemeHelper::setArchivedMessage($entity);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_plan_form_alter')]
  public function formPlanFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    /** @var \Drupal\Core\Entity\EntityForm $form_object */
    $form_object = $form_state->getFormObject();
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $entity = $form_object->getEntity();
    FarmUiThemeHelper::setArchivedMessage($entity);
  }

  /**
   * Implements hook_form_BASE_FORM_ID_alter().
   */
  #[Hook('form_quick_form_alter')]
  public function formQuickFormAlter(&$form, FormStateInterface $form_state, $form_id) {
    $form['#attached']['library'][] = 'farm_ui_theme/quick';
  }

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_farm_modules_form_alter')]
  public function formFarmModulesFormAlter(&$form, FormStateInterface $form_state, $form_id) {

    // Add the form-checkboxes class to module list checkbox fields so Gin does
    // not render each checkbox as a toggle element.
    foreach (['core', 'contrib', 'quick'] as $type) {
      if (isset($form[$type])) {
        $form[$type]['modules']['#attributes']['class'][] = 'form-checkboxes';
      }
    }
  }

}
