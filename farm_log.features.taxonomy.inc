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
    'farm_observation_types' => array(
      'name' => 'Farm Observation Types',
      'machine_name' => 'farm_observation_types',
      'description' => 'A vocabulary of farm animal types.',
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
    'farm_quantity_units' => array(
      'name' => 'Farm Quantity Units',
      'machine_name' => 'farm_quantity_units',
      'description' => 'A vocabulary of units for describing quantities.',
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
