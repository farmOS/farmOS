<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\Component\Uuid\Uuid;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Row;
use Drupal\migrate_plus\Plugin\migrate\process\EntityLookup;

/**
 * This plugin looks for existing asset entities.
 *
 * Lookups are performed on multiple fields to find the asset, in the following
 * order:
 *
 * - UUID
 * - ID tag
 * - Name
 * - ID (primary key)
 *
 * @codingStandardsIgnoreStart
 *
 * Example usage:
 * @code
 * destination:
 *   plugin: 'entity:log'
 * process:
 *   asset:
 *     plugin: asset_lookup
 *     source: asset
 * @endcode

 * @codingStandardsIgnoreEnd
 *
 * @MigrateProcessPlugin(
 *   id = "asset_lookup",
 *   handle_multiples = FALSE
 * )
 */
class AssetLookup extends EntityLookup {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Hard-code the entity type.
    $this->configuration['entity_type'] = 'asset';

    // If no bundle was specified, add all bundles.
    if (empty($this->configuration['bundle'])) {
      $asset_types = $this->entityTypeManager->getStorage('asset_type')->loadMultiple();
      foreach ($asset_types as $bundle => $asset_type) {
        $this->configuration['bundle'][] = $bundle;
      }
    }

    // Ignore case sensitivity.
    $this->configuration['ignore_case'] = TRUE;

    // Delegate to the parent entity_lookup plugin.
    return parent::transform($value, $migrate_executable, $row, $destination_property);
  }

  /**
   * {@inheritdoc}
   */
  protected function query($value) {

    // Trim the value.
    $value = trim($value);

    // If the value is empty, return NULL.
    if (empty($value)) {
      return NULL;
    }

    // We are going to attempt to look up the asset via multiple fields. If one
    // lookup fails, we will try the next, until all options are exhausted.
    $results = [];

    // First, if the value is a valid UUID, attempt a UUID lookup.
    if (Uuid::isValid($value)) {
      $this->lookupValueKey = 'uuid';
      $results = parent::query($value);
    }

    // If there are no results, try a lookup by ID tag.
    if (empty($results)) {
      $this->lookupValueKey = 'id_tag';
      $results = parent::query($value);
    }

    // If there are no results, try a lookup by name.
    if (empty($results)) {
      $this->lookupValueKey = 'name';
      $results = parent::query($value);
    }

    // If there are no results, and the value is a positive integer, try a
    // lookup by asset ID.
    if (empty($results) && is_numeric($value) && (int) $value == $value && (int) $value > 0) {
      $this->lookupValueKey = 'id';
      $results = parent::query($value);
    }

    // If there are still no results, throw an exception and skip the row.
    if (empty($results)) {
      throw new MigrateSkipRowException('Asset not found: ' . $value);
    }

    return $results;
  }

}
