<?php

declare(strict_types=1);

namespace Drupal\farm_farm\Hook;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_field\FarmFieldFactoryInterface;

/**
 * Field hook implementations for farm_farm.
 */
class FieldHooks {

  use StringTranslationTrait;

  public function __construct(
    protected FarmFieldFactoryInterface $farmFieldFactory,
    protected ModuleHandlerInterface $moduleHandler,
  ) {}

  /**
   * Implements hook_entity_base_field_info().
   */
  #[Hook('entity_base_field_info')]
  public function entityBaseFieldInfo(EntityTypeInterface $entity_type) {
    $fields = [];

    // Define common Farm reference field options.
    $options = [
      'type' => 'entity_reference',
      'label' => $this->t('Farm'),
      'description' => $this->t('What farm is this associated with?'),
      'target_type' => 'organization',
      'target_bundle' => 'farm',
      'multiple' => FALSE,
      'weight' => [
        'form' => 55,
        'view' => -5,
      ],
    ];

    // Add a Farm reference field to assets.
    if ($entity_type->id() == 'asset') {
      $fields['farm'] = $this->farmFieldFactory->baseFieldDefinition($options);

      // Add a constraint to ensure that assets are in the same farm as their
      // parents and children.
      $fields['farm']->addConstraint('AssetParentFarm');

      // Add a constraint to ensure that the farm does not change if there are
      // any movement logs that reference it (in either asset or location
      // field).
      $fields['farm']->addConstraint('AssetMovementFarm');

      // If the farm_group module is installed, add a constraint to ensure that
      // the farm does not change if there are any group assignment logs that
      // reference it (in either asset or group field).
      if ($this->moduleHandler->moduleExists('farm_group')) {
        $fields['farm']->addConstraint('AssetGroupAssignmentFarm');
      }
    }

    // Add a Farm reference field to plans.
    elseif ($entity_type->id() == 'plan') {
      $fields['farm'] = $this->farmFieldFactory->baseFieldDefinition($options);
    }

    return $fields;
  }

  /**
   * Implements hook_farm_import_csv_base_fields().
   */
  #[Hook('farm_import_csv_base_fields')]
  public function farmImportCsvBaseFields(string $entity_type) {
    $base_fields = [];

    // Add farm organization base field to asset CSV importers.
    if ($entity_type == 'asset') {
      $base_fields[] = 'farm';
    }

    return $base_fields;
  }

  /**
   * Implements hook_farm_ui_views_base_fields().
   */
  #[Hook('farm_ui_views_base_fields')]
  public function farmUiViewsBaseFields(string $entity_type) {
    $base_fields = [];

    // Add farm organization base field to farmOS asset Views.
    if ($entity_type == 'asset') {
      $base_fields[] = 'farm';
    }

    return $base_fields;
  }

}
