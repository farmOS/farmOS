<?php

/**
 * @file
 * Post update hooks for the plan module.
 */

/**
 * Install plan_record and plan_record_type entity types.
 */
function plan_post_update_install_plan_record(&$sandbox) {
  \Drupal::entityDefinitionUpdateManager()->installEntityType(
    \Drupal::entityTypeManager()->getDefinition('plan_record_type')
  );
  \Drupal::entityDefinitionUpdateManager()->installEntityType(
    \Drupal::entityTypeManager()->getDefinition('plan_record')
  );
}
