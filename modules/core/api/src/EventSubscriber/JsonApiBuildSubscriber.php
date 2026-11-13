<?php

declare(strict_types=1);

namespace Drupal\farm_api\EventSubscriber;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent;
use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * JSON API build subscriber for disabling resources.
 */
class JsonApiBuildSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected ModuleHandlerInterface $moduleHandler,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[ResourceTypeBuildEvents::BUILD] = [
      ['disableResources'],
      ['renameInternals'],
    ];
    return $events;
  }

  /**
   * Disable resources.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent $event
   *   The build resource build event.
   */
  public function disableResources(ResourceTypeBuildEvent $event) {
    $allowed_entity_types = $this->moduleHandler->invokeAll('farm_api_allow_resource_types');
    $this->moduleHandler->alter('farm_api_allow_resource_types', $allowed_entity_types);
    $entity_type = explode('--', $event->getResourceTypeName())[0];
    if (!in_array($entity_type, $allowed_entity_types)) {
      $event->disableResourceType();
    }
  }

  /**
   * Rename drupal_internal__* to internal__*.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent $event
   *   The build resource build event.
   */
  public function renameInternals(ResourceTypeBuildEvent $event) {

    // Iterate through all fields of this resource type.
    $fields = $event->getFields();
    foreach ($fields as $field) {

      // If the field's public name starts with "drupal_internal_", remove the
      // "drupal_" prefix, so it is just "internal_*".
      if (str_starts_with($field->getPublicName(), 'drupal_internal_')) {
        $event->setPublicFieldName($field, substr($field->getPublicName(), strlen('drupal_')));
      }
    }
  }

}
