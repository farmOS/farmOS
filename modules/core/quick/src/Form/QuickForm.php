<?php

namespace Drupal\farm_quick\Form;

use Drupal\Core\Form\BaseFormIdInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_quick\QuickFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form that renders quick forms.
 *
 * @ingroup farm
 */
class QuickForm extends FormBase implements BaseFormIdInterface {

  /**
   * The quick form manager.
   *
   * @var \Drupal\farm_quick\QuickFormManager
   */
  protected $quickFormManager;

  /**
   * The quick form ID.
   *
   * @var string
   */
  protected $quickFormId;

  /**
   * Class constructor.
   *
   * @param \Drupal\farm_quick\QuickFormManager $quick_form_manager
   *   The quick form manager.
   */
  public function __construct(QuickFormManager $quick_form_manager) {
    $this->quickFormManager = $quick_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.quick_form')
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
    $id = $this->getRouteMatch()->getParameter('id');
    return $this->getBaseFormId() . "_$id";
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
    return $this->quickFormManager->createInstance($id)->getLabel();
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
    return $this->quickFormManager->createInstance($id)->access($account);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    // Save the quick form ID.
    $this->quickFormId = $id;

    // Load the quick form.
    $quick_form = $this->quickFormManager->createInstance($id);
    $form = $quick_form->buildForm($form, $form_state);

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

    // Attach quick form CSS/JS.
    $form['#attached']['library'][] = 'farm_quick/quick';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormManager->createInstance($this->quickFormId)->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->quickFormManager->createInstance($this->quickFormId)->submitForm($form, $form_state);
  }

}
