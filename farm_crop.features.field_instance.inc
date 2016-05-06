<?php
/**
 * @file
 * farm_crop.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_crop_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'farm_asset-planting-field_farm_crop'.
  $field_instances['farm_asset-planting-field_farm_crop'] = array(
    'bundle' => 'planting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Enter the crop/variety that this is a planting of. As you type, you will have the option of selecting from crops/varieties that you\'ve entered in the past.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_crop',
    'label' => 'Crop/Variety',
    'required' => 1,
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
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'farm_asset-planting-field_farm_description'.
  $field_instances['farm_asset-planting-field_farm_description'] = array(
    'bundle' => 'planting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_description',
    'label' => 'Description',
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

  // Exported field_instance: 'farm_asset-planting-field_farm_images'.
  $field_instances['farm_asset-planting-field_farm_images'] = array(
    'bundle' => 'planting',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'colorbox',
        'settings' => array(
          'colorbox_caption' => 'auto',
          'colorbox_caption_custom' => '',
          'colorbox_gallery' => 'post',
          'colorbox_gallery_custom' => '',
          'colorbox_image_style' => '',
          'colorbox_multivalue_index' => NULL,
          'colorbox_node_style' => 'thumbnail',
          'colorbox_node_style_first' => 'large',
        ),
        'type' => 'colorbox',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_images',
    'label' => 'Photo(s)',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'farm/asset/planting',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'bar',
      ),
      'type' => 'image_image',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'farm_asset-planting-field_farm_season'.
  $field_instances['farm_asset-planting-field_farm_season'] = array(
    'bundle' => 'planting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Assign this planting to a season. You can manage these options in the ' . l('Seasons', 'admin/structure/taxonomy/farm_season') . ' vocabulary.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_plain',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_season',
    'label' => 'Season',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'log-farm_seeding-field_farm_notes'.
  $field_instances['log-farm_seeding-field_farm_notes'] = array(
    'bundle' => 'farm_seeding',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 4,
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
      'weight' => 4,
    ),
  );

  // Exported field_instance: 'log-farm_seeding-field_farm_planting'.
  $field_instances['log-farm_seeding-field_farm_planting'] = array(
    'bundle' => 'farm_seeding',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Seedings must reference a specific planting. You can view a list of all plantings, and add new ones, from the ' . l('Plantings list', 'farm/assets/plantings') . '.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'link' => 1,
        ),
        'type' => 'entityreference_label',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_planting',
    'label' => 'Planting',
    'required' => 1,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'log-farm_seeding-field_farm_quantity'.
  $field_instances['log-farm_seeding-field_farm_quantity'] = array(
    'bundle' => 'farm_seeding',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Describe the quantity of this seeding, using a value and a unit of measurement. For example, if you seeded 10 flats of lettuce, enter "10" in the Value field, and "flats" in the Units field. As you type the unit, you will have the option of selecting from units that you\'ve entered in the past.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'field_collection',
        'settings' => array(
          'view_mode' => 'full',
        ),
        'type' => 'field_collection_fields',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_quantity',
    'label' => 'Quantity',
    'required' => 1,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'field_collection',
      'settings' => array(),
      'type' => 'field_collection_embed',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'log-farm_seeding-field_farm_seed_source'.
  $field_instances['log-farm_seeding-field_farm_seed_source'] = array(
    'bundle' => 'farm_seeding',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Where were the seeds obtained?',
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
    'field_name' => 'field_farm_seed_source',
    'label' => 'Source/Supplier',
    'required' => 0,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'log-farm_transplanting-field_farm_notes'.
  $field_instances['log-farm_transplanting-field_farm_notes'] = array(
    'bundle' => 'farm_transplanting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 4,
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

  // Exported field_instance: 'log-farm_transplanting-field_farm_planting'.
  $field_instances['log-farm_transplanting-field_farm_planting'] = array(
    'bundle' => 'farm_transplanting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Transplantings must reference a specific planting. You can view a list of all plantings, and add new ones, from the ' . l('Plantings list', 'farm/assets/plantings') . '.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'link' => 1,
        ),
        'type' => 'entityreference_label',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_planting',
    'label' => 'Planting',
    'required' => 1,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'log-farm_transplanting-field_farm_quantity'.
  $field_instances['log-farm_transplanting-field_farm_quantity'] = array(
    'bundle' => 'farm_transplanting',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Describe the quantity of this transplanting, using a value and a unit of measurement. For example, if you transplanted 400 tomato plants into 4 inch pots, enter "400" in the Value field, and "4-inch pots" in the Units field. As you type the unit, you will have the option of selecting from units that you\'ve entered in the past.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'field_collection',
        'settings' => array(
          'view_mode' => 'full',
        ),
        'type' => 'field_collection_fields',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_quantity',
    'label' => 'Quantity',
    'required' => 1,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'field_collection',
      'settings' => array(),
      'type' => 'field_collection_embed',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'taxonomy_term-farm_crops-field_farm_companions'.
  $field_instances['taxonomy_term-farm_crops-field_farm_companions'] = array(
    'bundle' => 'farm_crops',
    'default_value' => array(),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 5,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_farm_companions',
    'label' => 'Companions',
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
      'weight' => 5,
    ),
  );

  // Exported field_instance: 'taxonomy_term-farm_crops-field_farm_crop_family'.
  $field_instances['taxonomy_term-farm_crops-field_farm_crop_family'] = array(
    'bundle' => 'farm_crops',
    'default_value' => array(),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 1,
      ),
      'entityreference_view_widget' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_farm_crop_family',
    'label' => 'Crop family',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'taxonomy',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'delimiter' => '',
        'limit' => 10,
        'min_length' => 0,
        'size' => 60,
      ),
      'type' => 'taxonomy_autocomplete',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'taxonomy_term-farm_crops-field_farm_images'.
  $field_instances['taxonomy_term-farm_crops-field_farm_images'] = array(
    'bundle' => 'farm_crops',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'image',
        'settings' => array(
          'image_link' => '',
          'image_style' => 'large',
        ),
        'type' => 'image',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_farm_images',
    'label' => 'Photo(s)',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'farm/term/crop',
      'file_extensions' => 'png gif jpg jpeg',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'bar',
      ),
      'type' => 'image_image',
      'weight' => 6,
    ),
  );

  // Exported field_instance:
  // 'taxonomy_term-farm_crops-field_farm_maturity_days'.
  $field_instances['taxonomy_term-farm_crops-field_farm_maturity_days'] = array(
    'bundle' => 'farm_crops',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
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
        'weight' => 3,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_farm_maturity_days',
    'label' => 'Days to maturity',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => ' day| days',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'number',
      'settings' => array(),
      'type' => 'number',
      'weight' => 3,
    ),
  );

  // Exported field_instance:
  // 'taxonomy_term-farm_crops-field_farm_transplant_days'.
  $field_instances['taxonomy_term-farm_crops-field_farm_transplant_days'] = array(
    'bundle' => 'farm_crops',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
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
        'weight' => 2,
      ),
    ),
    'entity_type' => 'taxonomy_term',
    'field_name' => 'field_farm_transplant_days',
    'label' => 'Days to transplant',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => ' day| days',
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

  // Translatables
  // Included for use with string extractors like potx.
  t('Assign this planting to a season. You can manage these options in the ' . l('Seasons', 'admin/structure/taxonomy/farm_season') . ' vocabulary.');
  t('Companions');
  t('Crop/Variety');
  t('Crop family');
  t('Days to maturity');
  t('Days to transplant');
  t('Describe the quantity of this seeding, using a value and a unit of measurement. For example, if you seeded 10 flats of lettuce, enter "10" in the Value field, and "flats" in the Units field. As you type the unit, you will have the option of selecting from units that you\'ve entered in the past.');
  t('Describe the quantity of this transplanting, using a value and a unit of measurement. For example, if you transplanted 400 tomato plants into 4 inch pots, enter "400" in the Value field, and "4-inch pots" in the Units field. As you type the unit, you will have the option of selecting from units that you\'ve entered in the past.');
  t('Description');
  t('Enter the crop/variety that this is a planting of. As you type, you will have the option of selecting from crops/varieties that you\'ve entered in the past.');
  t('Notes');
  t('Photo(s)');
  t('Planting');
  t('Quantity');
  t('Season');
  t('Seedings must reference a specific planting. You can view a list of all plantings, and add new ones, from the ' . l('Plantings list', 'farm/assets/plantings') . '.');
  t('Source/Supplier');
  t('Transplantings must reference a specific planting. You can view a list of all plantings, and add new ones, from the ' . l('Plantings list', 'farm/assets/plantings') . '.');
  t('Where were the seeds obtained?');

  return $field_instances;
}
