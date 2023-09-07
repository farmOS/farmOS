<?php

namespace Drupal\farm_ui_theme\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderCallbackInterface;

/**
 * Entity form for gin content form styling.
 */
class GinContentFormBase extends ContentEntityForm implements RenderCallbackInterface {

  /**
   * {@inheritdoc}
   */
  protected function getNewRevisionDefault() {
    return TRUE;
  }

  /**
   * Function that returns an array of tab definitions to add.
   *
   * @return array
   *   Array of tab definitions keyed by tab id. Each definition should
   *   provide a location, title and weight.
   */
  protected function getFieldGroups() {
    return [
      'entity' => [
        'location' => 'sidebar',
        'title' => 'Entity',
        'weight' => -10,
      ],
      'bundle' => [
        'location' => 'main',
        'title' => 'Bundle',
        'weight' => 0,
      ],
      'info' => [
        'location' => 'main',
        'title' => $this->t('Info'),
        'weight' => 20,
      ],
      'meta' => [
        'location' => 'sidebar',
        'title' => $this->t('Meta'),
        'weight' => 40,
      ],
      'location' => [
        'location' => 'main',
        'title' => $this->t('Location'),
        'weight' => 60,
      ],
      'file' => [
        'location' => 'main',
        'title' => $this->t('Files'),
        'weight' => 80,
      ],
      'revision' => [
        'location' => 'sidebar',
        'title' => $this->t('Revision information'),
        'weight' => 100,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Add process callback to alter form after GinContentFormHelper.
    $form['#process'][] = '::processContentForm';

    // @todo Need to add config schema for third party settings.
    // Only alter the form display if farm_ui_theme.use_field_group is TRUE
    // or if the form display is new and not saved.
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $form_display = $form_state->get('form_display');
    if (!$form_display || !$form_display->getThirdPartySetting('farm_ui_theme', 'use_field_group', $form_display->isNew())) {
      return $form;
    }

    // Add field groups.
    $field_groups = $this->getFieldGroups();
    if (!empty($field_groups)) {

      // Disable HTML5 validation on the form element since it does not work
      // with vertical tabs.
      $form['#attributes']['novalidate'] = 'novalidate';

      // Vary field group titles based on entity and bundle.
      if (isset($field_groups['entity'])) {
        $field_groups['entity']['title'] = $this->entity->getEntityType()->getLabel();
      }
      if (isset($field_groups['bundle'])) {
        $field_groups['bundle']['title'] = $this->entity->type->entity->label();
      }

      // Create parent for all tabs.
      $form['tabs'] = [
        '#type' => 'vertical_tabs',
        '#default_tab' => 'edit-setup',
      ];

      // Create tabs.
      foreach ($field_groups as $tab_id => $tab_info) {
        $tab_id = "{$tab_id}_field_group";
        $tab_group = $tab_info['location'] == 'sidebar' ? 'advanced' : 'tabs';
        $form[$tab_id] = [
          '#type' => 'details',
          '#title' => $tab_info['title'],
          '#group' => $tab_group,
          '#optional' => TRUE,
          '#weight' => $tab_info['weight'],
          '#open' => TRUE,
        ];
      }

      // Set field group for each display component.
      foreach ($form_display->getComponents() as $field_id => $options) {
        if (isset($form[$field_id]) && $render = $form_display->getRenderer($field_id)) {
          $tab_id = $render->getThirdPartySetting('farm_ui_theme', 'field_group', 'info');
          $form[$field_id]['#group'] = "{$tab_id}_field_group";
        }
      }
    }

    return $form;
  }

  /**
   * Process function to update form after GinContentFormHelper.
   *
   * @param array $form
   *   The form array to alter.
   *
   * @return array
   *   The form array.
   *
   * @see \Drupal\gin\GinContentFormHelper
   */
  public function processContentForm(array $form): array {

    // Disable the default meta group provided by Gin.
    unset($form['meta']);

    // Assign correct status group after GinContentFormHelper.
    if (isset($form['entity_field_group'])) {
      $form['status']['#group'] = 'entity_field_group';
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity_type_label = $this->entity->getEntityType()->getSingularLabel();
    $entity_url = $this->entity->toUrl()->setAbsolute()->toString();
    $this->messenger()->addMessage($this->t('Saved %entity_type_label: <a href=":url">%label</a>', ['%entity_type_label' => $entity_type_label, ':url' => $entity_url, '%label' => $this->entity->label()]));
    $form_state->setRedirectUrl($this->entity->toUrl());
    return $status;
  }

}
