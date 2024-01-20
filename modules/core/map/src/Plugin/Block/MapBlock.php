<?php

namespace Drupal\farm_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginDependencyTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a map block.
 *
 * @Block(
 *   id = "map_block",
 *   admin_label = @Translation("Map block"),
 * )
 */
class MapBlock extends BlockBase implements ContainerFactoryPluginInterface {

  use PluginDependencyTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a MapBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'map_type' => 'default',
      'map_behaviors' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Map type.
    $map_types = $this->entityTypeManager->getStorage('map_type')->loadMultiple();
    $map_type_options = array_map(function ($map_type) {
      /** @var \Drupal\farm_map\Entity\MapTypeInterface $map_type */
      return $map_type->label();
    }, $map_types);
    $form['map_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Map type'),
      '#options' => $map_type_options,
      '#default_value' => $this->configuration['map_type'],
    ];

    // Map behaviors.
    $map_behaviors = $this->entityTypeManager->getStorage('map_behavior')->loadMultiple();
    $map_behavior_options = array_map(function ($map_behavior) {
      /** @var \Drupal\farm_map\Entity\MapBehaviorInterface $map_behavior */
      return $map_behavior->label();
    }, $map_behaviors);
    $form['map_behaviors'] = [
      '#type' => 'select',
      '#title' => $this->t('Map behaviors'),
      '#description' => $this->t('Add additional behaviors to the map. This form lists all available behaviors, but be aware that some behaviors may require additional settings that must be provided by modules and will not work properly on their own. Note that behaviors may also be added to maps automatically by modules, even if they are not selected in this list. Using a custom map type is one way to avoid this.'),
      '#options' => $map_behavior_options,
      '#default_value' => $this->configuration['map_behaviors'],
      '#multiple' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Save map config values if no errors occurred.
    if (!$form_state->getErrors()) {
      $this->configuration['map_type'] = $form_state->getValue('map_type');
      $this->configuration['map_behaviors'] = array_keys($form_state->getValue('map_behaviors'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'farm_map',
      '#map_type' => $this->configuration['map_type'] ?? 'default',
      '#behaviors' => $this->configuration['map_behaviors'] ?? [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {

    // Add map type dependencies.
    /** @var \Drupal\farm_map\Entity\MapTypeInterface $map_type */
    $map_type = $this->entityTypeManager->getStorage('map_type')->load($this->configuration['map_type'] ?? 'default');
    $this->addDependencies($map_type->getDependencies());

    // Add map behavior dependencies.
    $map_behaviors = $this->configuration['map_behaviors'] ?? [];
    if (!empty($map_behaviors)) {
      /** @var \Drupal\farm_map\Entity\MapBehaviorInterface $behavior */
      foreach ($this->entityTypeManager->getStorage('map_behavior')->loadMultiple($map_behaviors) as $behavior) {
        $this->addDependencies($behavior->getDependencies());
      }
    }

    return $this->dependencies;
  }

}
