<?php

/**
 * @file
 * Post update hooks for the farmOS Quick Form module.
 */

/**
 * Install the new quick_form entity type.
 */
function farm_quick_post_update_install_quick_form_entity_type(&$sandbox) {
  \Drupal::entityDefinitionUpdateManager()->installEntityType(
    \Drupal::entityTypeManager()->getDefinition('quick_form')
  );
}
