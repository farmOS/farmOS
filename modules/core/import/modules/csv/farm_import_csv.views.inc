<?php

/**
 * @file
 * Provides Views data for the farmOS Import: CSV module.
 */

/**
 * Implements hook_views_data().
 */
function farm_import_csv_views_data() {
  $data = [];

  // Describe the farm_import_csv_entity table.
  $data['farm_import_csv_entity']['table']['group'] = t('CSV');

  // Provide a relationship to the file entity.
  $data['farm_import_csv_entity']['file_id'] = [
    'title' => t('CSV file'),
    'help' => t('Relate entities to the CSV file they were imported from.'),
    'relationship' => [
      'label' => t('CSV file'),
      'base' => 'file_managed',
      'base field' => 'fid',
      'id' => 'standard',
    ],
  ];

  // Provide a field and sort for the row number.
  $data['farm_import_csv_entity']['rownum'] = [
    'title' => t('Row number'),
    'help' => t('The CSV row number that this entity was imported from.'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  // Provide a contextual filter argument for the migration ID.
  $data['farm_import_csv_entity']['migration'] = [
    'title' => t('Migration ID'),
    'help' => t('The migration that imported this entity.'),
    'argument' => [
      'id' => 'string',
    ],
  ];

  // Create implicit joins to the asset, log, and taxonomy term data tables.
  $data['farm_import_csv_entity']['table']['join']['asset_field_data'] = [
    'left_field' => 'id',
    'field' => 'entity_id',
    'extra' => [
      [
        'field' => 'entity_type',
        'value' => 'asset',
      ],
    ],
  ];
  $data['farm_import_csv_entity']['table']['join']['log_field_data'] = [
    'left_field' => 'id',
    'field' => 'entity_id',
    'extra' => [
      [
        'field' => 'entity_type',
        'value' => 'log',
      ],
    ],
  ];
  $data['farm_import_csv_entity']['table']['join']['taxonomy_term_field_data'] = [
    'left_field' => 'tid',
    'field' => 'entity_id',
    'extra' => [
      [
        'field' => 'entity_type',
        'value' => 'taxonomy_term',
      ],
    ],
  ];

  return $data;
}
