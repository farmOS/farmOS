<?php

namespace Drupal\farm_quick\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;

/**
 * Form that renders quick form configuration forms.
 */
class QuickFormEntityForm extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state, string $plugin = NULL) {
    $form = parent::form($form, $form_state);

    // Add tabs if the quick form plugin is configurable.
    $tab_group = NULL;
    if ($this->entity->getPlugin()->isConfigurable()) {
      $form['tabs'] = [
        '#type' => 'vertical_tabs',
      ];
      $form['quick_form'] = [
        '#type' => 'details',
        '#title' => $this->t('Quick form'),
        '#group' => 'tabs',
      ];
      $tab_group = 'quick_form';

      // Render the plugin form in settings tab.
      $form['settings_tab'] = [
        '#type' => 'details',
        '#title' => $this->entity->getPlugin()->getLabel(),
        '#group' => 'tabs',
        '#weight' => 50,
      ];
      $form['settings'] = [
        '#tree' => TRUE,
        '#type' => 'container',
        '#group' => 'settings_tab',
      ];
      $form['settings'] = $this->entity->getPlugin()->buildConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $this->entity->label(),
      '#maxlength' => 255,
      '#required' => TRUE,
      '#group' => $tab_group,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\farm_quick\Entity\QuickFormInstance::load',
      ],
      '#disabled' => !$this->entity->isNew(),
      '#group' => $tab_group,
    ];

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#description' => $this->t('A brief description of this quick form.'),
      '#default_value' => $this->entity->getDescription(),
      '#group' => $tab_group,
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#description' => $this->t('Enable the quick form.'),
      '#default_value' => $this->entity->status(),
      '#group' => $tab_group,
    ];

    $form['helpText'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Help Text'),
      '#description' => $this->t('Help text to display for the quick form.'),
      '#default_value' => $this->entity->getHelpText(),
      '#group' => $tab_group,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Validate plugin form.
    if ($this->entity->getPlugin()->isConfigurable()) {
      $this->entity->getPlugin()->validateConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Submit plugin form.
    if ($this->entity->getPlugin()->isConfigurable()) {
      $this->entity->getPlugin()->submitConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }
  }

}
