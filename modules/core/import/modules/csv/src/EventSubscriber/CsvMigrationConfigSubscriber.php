<?php

namespace Drupal\farm_import_csv\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Routing\RouteBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Trigger a route rebuild when CSV importer configuration changes.
 */
class CsvMigrationConfigSubscriber implements EventSubscriberInterface {

  /**
   * The router builder.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected $routerBuilder;

  /**
   * Constructs the CsvMigrationConfigSubscriber.
   *
   * @param \Drupal\Core\Routing\RouteBuilderInterface $router_builder
   *   The router builder service.
   */
  public function __construct(RouteBuilderInterface $router_builder) {
    $this->routerBuilder = $router_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE][] = ['rebuildRouter'];
    $events[ConfigEvents::DELETE][] = ['rebuildRouter'];
    return $events;
  }

  /**
   * Informs the router builder a rebuild is needed when necessary.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The Event to process.
   */
  public function rebuildRouter(ConfigCrudEvent $event) {
    $config = $event->getConfig();
    if (str_starts_with($config->getName(), 'migrate_plus.migration.')) {
      $this->routerBuilder->setRebuildNeeded();
    }
  }

}
