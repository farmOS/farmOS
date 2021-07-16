<?php

namespace Drupal\farm_quick\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\farm_quick\QuickFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides menu links for quick forms.
 */
class QuickFormMenuLink extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The quick form manager.
   *
   * @var \Drupal\farm_quick\QuickFormManager
   */
  protected $quickFormManager;

  /**
   * FarmQuickMenuLink constructor.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   * @param \Drupal\farm_quick\QuickFormManager $quick_form_manager
   *   The quick form manager.
   */
  public function __construct(string $base_plugin_id, QuickFormManager $quick_form_manager) {
    $this->quickFormManager = $quick_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $base_plugin_id,
      $container->get('plugin.manager.quick_form')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add links for quick form.
    $quick_forms = $this->quickFormManager->getDefinitions();
    foreach ($quick_forms as $quick_form) {
      $links['farm.quick.' . $quick_form['id']] = [
        'title' => $quick_form['label'],
        'parent' => 'farm.quick',
        'route_name' => 'farm.quick.form',
        'route_parameters' => ['id' => $quick_form['id']],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
