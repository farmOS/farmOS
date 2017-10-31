<?php
/**
 * @file
 * farm_log_sale.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_log_sale_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'log-farm_sale-field_farm_asset'.
  $field_instances['log-farm_sale-field_farm_asset'] = array(
    'bundle' => 'farm_sale',
    'deleted' => 0,
    'description' => 'What assets do this sale log pertain to?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'bypass_access' => 0,
          'link' => 1,
        ),
        'type' => 'entityreference_label',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_asset',
    'label' => 'Assets',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference_view_widget',
      'settings' => array(
        'allow_duplicates' => 0,
        'close_modal' => 1,
        'pass_argument' => 1,
        'pass_arguments' => '',
        'rendered_entity' => 0,
        'view' => 'farm_asset_entityreference_view|entityreference_view_widget',
        'view_mode' => 'full',
      ),
      'type' => 'entityreference_view_widget',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_customer'.
  $field_instances['log-farm_sale-field_farm_customer'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_customer',
    'label' => 'Customer',
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

  // Exported field_instance: 'log-farm_sale-field_farm_files'.
  $field_instances['log-farm_sale-field_farm_files'] = array(
    'bundle' => 'farm_sale',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'file',
        'settings' => array(),
        'type' => 'file_table',
        'weight' => 13,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_files',
    'label' => 'Files',
    'required' => 0,
    'settings' => array(
      'description_field' => 1,
      'file_directory' => 'farm/log/sale',
      'file_extensions' => '',
      'max_filesize' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'multiupload_filefield_widget',
      'settings' => array(
        'progress_indicator' => 'bar',
      ),
      'type' => 'file_mfw',
      'weight' => 8,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_images'.
  $field_instances['log-farm_sale-field_farm_images'] = array(
    'bundle' => 'farm_sale',
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
        'weight' => 12,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_images',
    'label' => 'Photos',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'farm/log/sale',
      'file_extensions' => '',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'multiupload_imagefield_widget',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'bar',
      ),
      'type' => 'image_miw',
      'weight' => 7,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_inventory'.
  $field_instances['log-farm_sale-field_farm_inventory'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Use this to set or adjust asset inventory levels along with this log.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'field_collection',
        'settings' => array(
          'view_mode' => 'full',
        ),
        'type' => 'field_collection_fields',
        'weight' => 8,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_inventory',
    'label' => 'Inventory adjustments',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'field_collection',
      'settings' => array(),
      'type' => 'field_collection_embed',
      'weight' => 12,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_invoice_number'.
  $field_instances['log-farm_sale-field_farm_invoice_number'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_invoice_number',
    'label' => 'Invoice number',
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
      'weight' => 4,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_log_category'.
  $field_instances['log-farm_sale-field_farm_log_category'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Use this to organize your logs into categories for easier searching and filtering later.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_plain',
        'weight' => 10,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_log_category',
    'label' => 'Log category',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 5,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_log_owner'.
  $field_instances['log-farm_sale-field_farm_log_owner'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Optionally assign this task to one or more people.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'bypass_access' => FALSE,
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 11,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_log_owner',
    'label' => 'Assigned to',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 10,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_lot_number'.
  $field_instances['log-farm_sale-field_farm_lot_number'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'If this sale has a lot number associated with it, enter it here.',
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
    'field_name' => 'field_farm_lot_number',
    'label' => 'Sale lot number',
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
      'weight' => 5,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_notes'.
  $field_instances['log-farm_sale-field_farm_notes'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 9,
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
      'weight' => 10,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_quantity_units'.
  $field_instances['log-farm_sale-field_farm_quantity_units'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'What is the unit of measurement for the quantity entered?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 5,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_quantity_units',
    'label' => 'Units',
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
      'weight' => 7,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_quantity_value'.
  $field_instances['log-farm_sale-field_farm_quantity_value'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'How many were sold?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'fraction',
        'settings' => array(
          'auto_precision' => TRUE,
          'precision' => 0,
          'prefix_suffix' => TRUE,
        ),
        'type' => 'fraction_decimal',
        'weight' => 4,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_quantity_value',
    'label' => 'Quantity',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'fraction',
      'settings' => array(
        'auto_precision' => 1,
        'precision' => 0,
      ),
      'type' => 'fraction_decimal',
      'weight' => 6,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_total_price'.
  $field_instances['log-farm_sale-field_farm_total_price'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'fraction',
        'settings' => array(
          'auto_precision' => TRUE,
          'precision' => 0,
          'prefix_suffix' => TRUE,
        ),
        'type' => 'fraction_decimal',
        'weight' => 7,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_total_price',
    'label' => 'Total price',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'fraction',
      'settings' => array(
        'auto_precision' => 1,
        'precision' => 0,
      ),
      'type' => 'fraction_decimal',
      'weight' => 9,
    ),
  );

  // Exported field_instance: 'log-farm_sale-field_farm_unit_price'.
  $field_instances['log-farm_sale-field_farm_unit_price'] = array(
    'bundle' => 'farm_sale',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'fraction',
        'settings' => array(
          'auto_precision' => TRUE,
          'precision' => 0,
          'prefix_suffix' => TRUE,
        ),
        'type' => 'fraction_decimal',
        'weight' => 6,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_unit_price',
    'label' => 'Unit price',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'fraction',
      'settings' => array(
        'auto_precision' => 1,
        'precision' => 0,
      ),
      'type' => 'fraction_decimal',
      'weight' => 8,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Assets');
  t('Assigned to');
  t('Customer');
  t('Files');
  t('How many were sold?');
  t('If this sale has a lot number associated with it, enter it here.');
  t('Inventory adjustments');
  t('Invoice number');
  t('Log category');
  t('Notes');
  t('Optionally assign this task to one or more people.');
  t('Photos');
  t('Quantity');
  t('Sale lot number');
  t('Total price');
  t('Unit price');
  t('Units');
  t('Use this to organize your logs into categories for easier searching and filtering later.');
  t('Use this to set or adjust asset inventory levels along with this log.');
  t('What assets do this sale log pertain to?');
  t('What is the unit of measurement for the quantity entered?');

  return $field_instances;
}
