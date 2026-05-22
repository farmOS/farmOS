<?php

declare(strict_types=1);

namespace Drupal\farm_category\Hook;

use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_field\FarmFieldFactoryInterface;

/**
 * Field hook implementations for farm_category.
 */
class FieldHooks {

  use AutowireTrait;
  use StringTranslationTrait;

  public function __construct(
    protected FarmFieldFactoryInterface $farmFieldFactory,
  ) {}

  /**
   * Implements hook_entity_base_field_info().
   */
  #[Hook('entity_base_field_info')]
  public function entityBaseFieldInfo(EntityTypeInterface $entity_type) {

    // Add category base field to all asset and log types.
    $fields = [];
    if (in_array($entity_type->id(), ['asset', 'log'])) {
      $category_info = [
        'type' => 'entity_reference',
        'label' => $this->t('Category'),
        'description' => $this->t('Use this to organize your records into categories for easier searching and filtering later.'),
        'target_type' => 'taxonomy_term',
        'multiple' => TRUE,
        'weight' => [
          'view' => 80,
        ],
        'form_display_options' => [
          'type' => 'options_select',
          'weight' => 10,
        ],
      ];
      if ($entity_type->id() == 'asset') {
        $category_info['target_bundle'] = 'asset_category';
      }
      elseif ($entity_type->id() == 'log') {
        $category_info['target_bundle'] = 'log_category';
      }
      $fields['category'] = $this->farmFieldFactory->baseFieldDefinition($category_info);
    }
    return $fields;
  }

}
