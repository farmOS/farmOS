<?php

namespace Drupal\farm_quick\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\farm_quick\QuickFormPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Page that renders links to create instances of quick form plugins.
 */
class QuickFormAddPage extends ControllerBase {

  /**
   * The quick form plugin manager.
   *
   * @var \Drupal\farm_quick\QuickFormPluginManager
   */
  protected $quickFormPluginManager;

  /**
   * Constructs a new QuickFormAddPage object.
   */
  public function __construct(QuickFormPluginManager $quick_form_plugin_manager) {
    $this->quickFormPluginManager = $quick_form_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.quick_form'),
    );
  }

  /**
   * Add quick form page callback.
   *
   * @return array
   *   Render array.
   */
  public function addPage(): array {

    $render = [
      '#theme' => 'entity_add_list',
      '#bundles' => [],
      '#cache' => [
        'tags' => $this->quickFormPluginManager->getCacheTags(),
      ],
    ];

    // Filter to configurable quick form plugins.
    $plugins = array_filter($this->quickFormPluginManager->getDefinitions(), function (array $plugin) {
      if (($instance = $this->quickFormPluginManager->createInstance($plugin['id'])) && $instance->isConfigurable()) {
        return TRUE;
      }
      return FALSE;
    });

    if (empty($plugins)) {
      $render['#add_bundle_message'] = $this->t('No quick forms are available. Enable a module that provides quick forms.');
    }

    // Add link for each configurable plugin.
    foreach ($plugins as $plugin_id => $plugin) {
      $render['#bundles'][$plugin_id] = [
        'label' => Html::escape($plugin['label']),
        'description' => Html::escape($plugin['description']) ?? '',
        'add_link' => Link::createFromRoute($plugin['label'], 'farm_quick.add_form', ['plugin' => $plugin_id]),
      ];
    }

    return $render;
  }

}
