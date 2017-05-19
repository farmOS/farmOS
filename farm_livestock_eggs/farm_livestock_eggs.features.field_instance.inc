<?php
/**
 * @file
 * farm_livestock_eggs.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_livestock_eggs_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'log-farm_eggs-field_farm_animal_group'.
  $field_instances['log-farm_eggs-field_farm_animal_group'] = array(
    'bundle' => 'farm_eggs',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'What group/flock were these eggs collected from?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_plain',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_animal_group',
    'label' => 'Group',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'taxonomy',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'size' => 60,
      ),
      'type' => 'taxonomy_autocomplete',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'log-farm_eggs-field_farm_egg_qty'.
  $field_instances['log-farm_eggs-field_farm_egg_qty'] = array(
    'bundle' => 'farm_eggs',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'How many eggs were collected?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'number',
        'settings' => array(
          'decimal_separator' => '.',
          'prefix_suffix' => TRUE,
          'scale' => 0,
          'thousand_separator' => ' ',
        ),
        'type' => 'number_integer',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_egg_qty',
    'label' => 'Quantity',
    'required' => 1,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => ' egg(s)',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'number',
      'settings' => array(),
      'type' => 'number',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'log-farm_eggs-field_farm_notes'.
  $field_instances['log-farm_eggs-field_farm_notes'] = array(
    'bundle' => 'farm_eggs',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_notes',
    'label' => 'Notes',
    'required' => 0,
    'settings' => array(
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => 3,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Group');
  t('How many eggs were collected?');
  t('Notes');
  t('Quantity');
  t('What group/flock were these eggs collected from?');

  return $field_instances;
}
