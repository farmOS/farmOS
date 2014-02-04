<?php
/**
 * @file
 * farm_log.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_log_taxonomy_default_vocabularies() {
  return array(
    'farm_areas' => array(
      'name' => 'Areas',
      'machine_name' => 'farm_areas',
      'description' => 'Distinct fields or beds that crops will be planted into.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_containers' => array(
      'name' => 'Containers',
      'machine_name' => 'farm_containers',
      'description' => 'Various types of containers for growing crops.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_crops' => array(
      'name' => 'Crops',
      'machine_name' => 'farm_crops',
      'description' => 'A taxonomy of crop types and varieties.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_fertilizers' => array(
      'name' => 'Fertilizers',
      'machine_name' => 'farm_fertilizers',
      'description' => 'Materials used to fertilize crops, areas, etc.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_threats' => array(
      'name' => 'Threats',
      'machine_name' => 'farm_threats',
      'description' => 'External forces that threaten your crops.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
  );
}
