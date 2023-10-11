<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Row;
use Drupal\migrate_plus\Plugin\migrate\process\EntityLookup;

/**
 * This plugin looks for existing term entities.
 *
 * @codingStandardsIgnoreStart
 *
 * Example usage:
 * @code
 * destination:
 *   plugin: 'entity:log'
 * process:
 *   asset:
 *     plugin: term_lookup
 *     source: term
 * @endcode

 * @codingStandardsIgnoreEnd
 *
 * @MigrateProcessPlugin(
 *   id = "term_lookup",
 *   handle_multiples = FALSE
 * )
 */
class TermLookup extends EntityLookup {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Hard-code the entity type.
    $this->configuration['entity_type'] = 'taxonomy_term';

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

    // Attempt to look up the term with the parent query() method.
    $results = parent::query($value);

    // If there are no results, throw an exception and skip the row.
    if (empty($results)) {
      throw new MigrateSkipRowException('Term not found: ' . $value);
    }

    return $results;
  }

}
