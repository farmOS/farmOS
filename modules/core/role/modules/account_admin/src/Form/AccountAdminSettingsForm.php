<?php

namespace Drupal\farm_role_account_admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a settings form for the Account Admin Role module.
 */
class AccountAdminSettingsForm extends ConfigFormbase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'farm_role_account_admin.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_role_account_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateinterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['allow_peer_role_assignment'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow peer role assignment'),
      '#description' => $this->t('Allow users with the Account Admin role to assign/revoke the Account Admin role.'),
      '#default_value' => $config->get('allow_peer_role_assignment'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('allow_peer_role_assignment', $form_state->getValue('allow_peer_role_assignment'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
