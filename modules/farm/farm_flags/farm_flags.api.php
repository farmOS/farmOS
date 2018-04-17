<?php

/**
 * @file
 * Hooks provided by farm_flags.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_flags Farm flag module integrations.
 *
 * Module integrations with the farm_flags module.
 */

/**
 * @defgroup farm_flags_hooks Farm flag's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_flags.
 */

/**
 * Provide a list of flags that can be applied to records for filtering.
 *
 * @return array
 *   Returns an associative array of flags, with machine name and translatable.
 */
function hook_farm_flags() {
  return array(
    'priority' => t('Priority'),
    'review' => t('Needs Review'),
    'organic' => t('Organic'),
    'notorganic' => t('Not Organic'),
  );
}

/**
 * Allow modules to alter the classes that are added to flags when they are
 * displayed in farmOS.
 */
function hook_farm_flags_classes_alter($flag, &$classes) {
  if ($flag == 'priority') {
    $classes[] = 'my-priority-class';
  }
}

/**
 * @}
 */
