<?php

namespace Drupal\farm_quick\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_quick\Plugin\QuickForm\ConfigurableQuickFormInterface;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Form that renders quick form configuration forms.
 *
 * @ingroup farm
 */
class ConfigureQuickForm extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface
   */
  protected $entity;

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

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
   * Get the title of the quick form.
   *
   * @param string $quick_form
   *   The quick form ID.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Quick form title.
   */
  public function getTitle(string $quick_form) {
    $quick_form_title = NULL;
    if ($quick_form = $this->getQuickFormInstance($quick_form)) {
      $quick_form_title = $quick_form->getLabel();
    }
    return $this->t('Configure @quick_form', ['@quick_form' => $quick_form_title]);
  }

  /**
   * Checks access for configuration of a specific quick form.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param string|null $quick_form
   *   The quick form ID.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, string $quick_form = NULL) {

    // Get a quick form config entity.
    if ($quick_form !== NULL) {
      $quick_form = $this->getQuickFormInstance($quick_form);
    }

    // Raise 404 if no quick form exists. This is the case with a quick form
    // ID that is not a valid quick form plugin ID.
    if ($quick_form === NULL) {
      throw new ResourceNotFoundException();
    }

    // Deny access if the quick form plugin is not configurable.
    if (!$quick_form->getPlugin() instanceof ConfigurableQuickFormInterface) {
      return AccessResult::forbidden();
    }

    // Check the update quick_form permission.
    $configure_form_access = AccessResult::allowedIfHasPermissions($account, ['update quick_form']);
    return $quick_form->getPlugin()->access($account)->andIf($configure_form_access);
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['settings'] = [
      '#tree' => TRUE,
    ];
    $form['settings'] = $this->entity->getPlugin()->buildConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $this->entity->getPlugin()->validateConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->entity->getPlugin()->submitConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityFromRouteMatch(RouteMatchInterface $route_match, $entity_type_id) {
    $entity = NULL;
    if ($route_match->getRawParameter($entity_type_id) !== NULL) {
      $entity = $this->getQuickFormInstance($route_match->getParameter($entity_type_id));
    }
    return $entity;
  }

  /**
   * Helper function to get a quick form instance.
   *
   * @param string $quick_form_id
   *   The quick form ID.
   *
   * @return \Drupal\farm_quick\Entity\QuickFormInstanceInterface|null
   *   The quick form instance or NULL if does not exist.
   */
  protected function getQuickFormInstance(string $quick_form_id) {
    return $this->quickFormInstanceManager->getInstance($quick_form_id);
  }

}
