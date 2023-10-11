<?php

namespace Drupal\farm_quick\Plugin\QuickForm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Psr\Container\ContainerInterface;

/**
 * Base class for quick forms.
 */
class QuickFormBase extends PluginBase implements QuickFormInterface, ContainerFactoryPluginInterface {

  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * The quick form ID.
   *
   * @var string
   */
  protected string $quickId;

  /**
   * Constructs a QuickFormBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  final public function setQuickId(string $id) {
    return $this->quickId = $id;
  }

  /**
   * {@inheritdoc}
   */
  final public function getQuickId() {
    return $this->quickId ?? $this->getPluginId();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return $this->getQuickId();
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->pluginDefinition['description'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getHelpText() {
    return $this->pluginDefinition['helpText'] ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getPermissions() {
    return $this->pluginDefinition['permissions'] ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    $permissions = $this->getPermissions();
    return AccessResult::allowedIfHasPermissions($account, $permissions);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Submit is optional, but presumably this will be overridden.
  }

}
