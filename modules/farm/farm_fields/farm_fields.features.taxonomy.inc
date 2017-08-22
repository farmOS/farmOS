<?php
/**
 * @file
 * farm_fields.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_fields_taxonomy_default_vocabularies() {
  return array(
    'farm_log_categories' => array(
      'name' => 'Farm Log Categories',
      'machine_name' => 'farm_log_categories',
      'description' => 'Vocabulary of farm log categories.',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
      'rdf_mapping' => array(
        'rdftype' => array(
          0 => 'skos:ConceptScheme',
        ),
        'name' => array(
          'predicates' => array(
            0 => 'dc:title',
          ),
        ),
        'description' => array(
          'predicates' => array(
            0 => 'rdfs:comment',
          ),
        ),
      ),
    ),
  );
}
