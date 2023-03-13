<?php

namespace Drupal\farm_quick\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Form that renders quick form configuration forms.
 *
 * @ingroup farm
 */
class ConfigureQuickForm extends QuickForm {

  /**
   * {@inheritdoc}
   */
  public function getBaseFormId() {
    return 'configure_quick_form';
  }

  /**
   * Get the title of the quick form.
   *
   * @param string $id
   *   The quick form ID.
   *
   * @return string
   *   Quick form title.
   */
  public function getTitle(string $id) {
    $quick_form_title = $this->quickFormInstanceManager->createInstance($id)->getLabel();
    return $this->t('Configure @quick_form', ['@quick_form' => $quick_form_title]);
  }

  /**
   * Checks access for configuration of a specific quick form.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param string $id
   *   The quick form ID.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, string $id) {
    $configure_form_access = AccessResult::allowedIfHasPermissions($account, ['configure quick forms']);
    return parent::access($account, $id)->andIf($configure_form_access);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    // Save the quick form ID.
    $this->quickFormId = $id;

    // Load the quick form's configuration form.
    $form = $this->quickFormInstanceManager->createInstance($id)->getPlugin()->buildConfigurationForm($form, $form_state);

    // Add a submit button.
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormInstanceManager->createInstance($this->quickFormId)->getPlugin()->validateConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormInstanceManager->createInstance($this->quickFormId)->getPlugin()->submitConfigurationForm($form, $form_state);
  }

}
