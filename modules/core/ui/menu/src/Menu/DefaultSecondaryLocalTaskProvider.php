<?php

namespace Drupal\farm_ui_menu\Menu;

use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\entity\Menu\EntityLocalTaskProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides default entity tasks as secondary local tasks.
 */
class DefaultSecondaryLocalTaskProvider implements EntityLocalTaskProviderInterface, EntityHandlerInterface {

  use StringTranslationTrait;

  /**
   * The entity type.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $entityType;

  /**
   * Constructs a DefaultEntityLocalTaskProvider object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation.
   */
  public function __construct(EntityTypeInterface $entity_type, TranslationInterface $string_translation) {
    $this->entityType = $entity_type;
    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static($entity_type, $container->get('string_translation'));
  }

  /**
   * {@inheritdoc}
   */
  public function buildLocalTasks(EntityTypeInterface $entity_type) {

    // Convert templates labels for task IDs.
    // Note: delete-form was intentionally omitted, to match core. See #1834002.
    $link_templates = [];
    foreach (['canonical', 'edit-form', 'duplicate-form', 'version-history'] as $rel) {
      if ($entity_type->hasLinkTemplate($rel)) {
        $link_templates[] = str_replace('-', '_', $rel);
      }
    }

    // Build local tasks.
    // The entity type must provide a canonical template and at least one
    // additional template to continue.
    $tasks = [];
    if (in_array('canonical', $link_templates) && count($link_templates) > 1) {

      // Build the parent route and ID for secondary tabs.
      $entity_type_id = $entity_type->id();
      $parent_route = "entity.$entity_type_id.canonical";
      $parent = "entity.entity_tasks:$parent_route";

      $titles = [
        'canonical' => $this->t('View'),
        'edit_form' => $this->t('Edit'),
        'duplicate_form' => $this->t('Duplicate'),
        'version_history' => $this->t('Revisions'),
      ];

      // Build local tasks for each template.
      $weight = 0;
      foreach ($link_templates as $rel) {
        $route_name = "entity.$entity_type_id.$rel";

        // Add a base and secondary task for canonical templates.
        if ($rel == 'canonical') {

          // Primary task with special class to use the bundle label.
          $tasks[$route_name] = [
            'class' => EntityTypeLabelLocalTask::class,
            'options' => [
              'entity_type' => $entity_type_id,
            ],
            'title' => $entity_type->getLabel(),
            'route_name' => $route_name,
            'base_route' => $route_name,
            'weight' => $weight,
          ];

          // Secondary task for the "View" tab.
          $tasks["entity.$entity_type_id.canonical.secondary"] = [
            'title' => $this->t('View'),
            'route_name' => $route_name,
            'parent_id' => $parent,
            'weight' => $weight,
          ];
        }

        // Add secondary tabs for all other templates.
        else {
          $tasks[$route_name] = [
            'title' => $titles[$rel],
            'route_name' => $route_name,
            'parent_id' => $parent,
            'weight' => $weight,
          ];
        }

        $weight += 10;
      }
    }
    return $tasks;
  }

}
