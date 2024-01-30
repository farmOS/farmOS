<?php

namespace Drupal\farm_quick\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides task links for farmOS Quick Forms.
 */
class QuickFormTaskLink extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * QuickFormTaskLink constructor.
   *
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_instance_manager
   *   The quick form plugin manager.
   */
  public function __construct(QuickFormInstanceManagerInterface $quick_form_instance_manager) {
    $this->quickFormInstanceManager = $quick_form_instance_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('quick_form.instance_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Load quick forms.
    $quick_forms = $this->quickFormInstanceManager->getInstances();

    // Add links for each quick form.
    foreach ($quick_forms as $id => $quick_form) {
      $route_name = 'farm.quick.' . $id;
      $links[$route_name] = [
        'title' => $this->t('Quick form'),
        'route_name' => $route_name,
        'base_route' => $route_name,
        'weight' => 0,
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
