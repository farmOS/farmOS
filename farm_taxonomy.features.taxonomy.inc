<?php
/**
 * @file
 * farm_taxonomy.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_taxonomy_taxonomy_default_vocabularies() {
  return array(
    'farm_areas' => array(
      'name' => 'Fields+Beds',
      'machine_name' => 'farm_areas',
      'description' => 'Distinct fields or beds that crops are planted into.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_crops' => array(
      'name' => 'Crops',
      'machine_name' => 'farm_crops',
      'description' => 'A taxonomy of crop types and varieties.',
      'hierarchy' => '1',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_fertilizers' => array(
      'name' => 'Fertilizers',
      'machine_name' => 'farm_fertilizers',
      'description' => 'Fertilizers that are used to nourish crops, fields, beds, etc.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_priority' => array(
      'name' => 'Priority Levels',
      'machine_name' => 'farm_priority',
      'description' => 'Levels of priority used to describe tasks.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_quantity_units' => array(
      'name' => 'Quantity Units',
      'machine_name' => 'farm_quantity_units',
      'description' => 'A vocabulary of units for describing quantities.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
    'farm_threats' => array(
      'name' => 'Threats',
      'machine_name' => 'farm_threats',
      'description' => 'External forces that threaten your stuff.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
    ),
  );
}
