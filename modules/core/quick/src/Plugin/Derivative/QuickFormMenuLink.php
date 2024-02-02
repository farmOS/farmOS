<?php

namespace Drupal\farm_quick\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Utility\Html;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides menu links for quick forms.
 */
class QuickFormMenuLink extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * FarmQuickMenuLink constructor.
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
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('quick_form.instance_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Load quick forms.
    /** @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface[] $quick_forms */
    $quick_forms = $this->quickFormInstanceManager->getInstances();

    // Add a top level menu parent.
    if (!empty($quick_forms)) {
      $links['farm.quick'] = [
        'title' => 'Quick forms',
        'route_name' => 'farm.quick',
        'weight' => -100,
      ] + $base_plugin_definition;
    }

    // Add a link for each quick form.
    foreach ($quick_forms as $id => $quick_form) {

      // Skip disabled quick forms.
      if (!$quick_form->status()) {
        continue;
      }

      // Create link.
      $route_id = 'farm.quick.' . $id;
      $links[$route_id] = [
        'title' => Html::escape($quick_form->getLabel()),
        'parent' => 'farm.quick:farm.quick',
        'route_name' => $route_id,
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
