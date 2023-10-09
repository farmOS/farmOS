<?php

namespace Drupal\farm_quick\Form;

use Drupal\Core\Form\BaseFormIdInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Form that renders quick forms.
 *
 * @ingroup farm
 */
class QuickForm extends FormBase implements BaseFormIdInterface {

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * The quick form ID.
   *
   * @var string
   */
  protected $quickFormId;

  /**
   * Class constructor.
   *
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_instance_manager
   *   The quick form instance manager.
   */
  public function __construct(QuickFormInstanceManagerInterface $quick_form_instance_manager) {
    $this->quickFormInstanceManager = $quick_form_instance_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('quick_form.instance_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getBaseFormId() {
    return 'quick_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    $form_id = $this->getBaseFormId();
    $id = $this->getRouteMatch()->getParameter('id');
    if (!is_null($id)) {
      $form_id .= '_' . $this->quickFormInstanceManager->getInstance($id)->getPlugin()->getFormId();
    }
    return $form_id;
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
    return $this->quickFormInstanceManager->getInstance($id)->getLabel();
  }

  /**
   * Checks access for a specific quick form.
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
    if ($quick_form = $this->quickFormInstanceManager->getInstance($id)) {
      return $quick_form->getPlugin()->access($account);
    }

    // Raise 404 if the quick form does not exist.
    throw new ResourceNotFoundException();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    // Save the quick form ID.
    $this->quickFormId = $id;

    // Load the quick form.
    $form = $this->quickFormInstanceManager->getInstance($id)->getPlugin()->buildForm($form, $form_state);

    // Add a submit button, if one wasn't provided.
    if (empty($form['actions']['submit'])) {
      $form['actions'] = [
        '#type' => 'actions',
        '#weight' => 1000,
      ];
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormInstanceManager->getInstance($this->quickFormId)->getPlugin()->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormInstanceManager->getInstance($this->quickFormId)->getPlugin()->submitForm($form, $form_state);
  }

}
