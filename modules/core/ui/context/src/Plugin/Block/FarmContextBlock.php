<?php

namespace Drupal\farm_ui_context\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\farm_ui_context\FarmContextManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a farm context block.
 *
 * @Block(
 *   id = "farm_context_block",
 *   admin_label = @Translation("Farm Context"),
 *   category = @Translation("Farm"),
 *   context_definitions = {
 *     "asset" = @ContextDefinition(
 *        "entity:asset",
 *        label = @Translation("Asset"),
 *        required = FALSE
 *     ),
 *     "log" = @ContextDefinition(
 *        "entity:log",
 *        label = @Translation("Log"),
 *        required = FALSE
 *      ),
 *     "plan" = @ContextDefinition(
 *        "entity:plan",
 *        label = @Translation("Plan"),
 *        required = FALSE
 *      ),
 *     "term" = @ContextDefinition(
 *        "entity:taxonomy_term",
 *        label = @Translation("Term"),
 *        required = FALSE
 *     ),
 *   }
 * )
 */
class FarmContextBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The farm context manager.
   *
   * @var \Drupal\farm_ui_context\FarmContextManagerInterface
   */
  protected $farmContextManager;

  /**
   * Constructs a new FarmContextBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\farm_ui_context\FarmContextManagerInterface $farm_context_manager
   *   The farm context manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $module_handler, FarmContextManagerInterface $farm_context_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $module_handler;
    $this->farmContextManager = $farm_context_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler'),
      $container->get('plugin.manager.farm_context'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getContextDefinitions() {
    $definitions = parent::getContextDefinitions();

    // Remove the plan context definition if the plan module does not exist.
    // This is a workaround so this module does not need to depend on plan.
    if (!$this->moduleHandler->moduleExists('plan')) {
      unset($definitions['plan']);
    }
    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getContextMapping() {
    $mapping = parent::getContextMapping();

    // Add a default context mapping for plan entities. This is needed because
    // we don't want to save the plan context mapping by default, but we do
    // want plan entities to be included if the module is later enabled.
    if ($this->moduleHandler->moduleExists('plan') && !isset($mapping['plan'])) {
      $mapping['plan'] = "@plan.plan_route_context:plan";
    }
    // Ensure there is no context mapping if the plan module does not exist.
    else {
      unset($mapping['plan']);
    }

    return $mapping;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Filter out contexts that do not have a value.
    // We need to define all entity contexts as optional for the block,
    // but only pass on contexts that returned a value.
    $contexts = $this->getContexts();
    $valid_contexts = array_filter($contexts, function ($context) {
      return $context->hasContextValue();
    });

    // Gather all farm context messages.
    $messages = $this->farmContextManager->getMessages('farm_context_block', $valid_contexts);

    // Bail if no messages.
    if (count($messages) === 0) {
      return [];
    }

    // Return a build array with the context messages.
    $title = [
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#attributes' => [
      ],
      '#items' => [],
    ];
    $body = [
      '#theme' => 'item_list',
      '#type' => 'ul',
      '#attributes' => [
      ],
      '#items' => [],
    ];
    foreach ($messages as $message) {
      $link_string = implode(', ', $message['links'] ?? []);
      $title['#items'][] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => "({$message['type']}) {$message['message']} $link_string",
      ];
      $body['#items'][] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => "({$message['type']}) {$message['long_message']} $link_string",
      ];
    }

    // Build a details element.
    $build = [
      '#type' => 'details',
      '#title' => $title,
      '#open' => FALSE,
      'body' => $body,
    ];

    // @todo determine cache strategy.
    $build['#cache'] = [
      'max-age' => 0,
    ];
    return $build;
  }

}
