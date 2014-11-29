<?php
/**
 * @file
 * farm_log_crops.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_log_crops_taxonomy_default_vocabularies() {
  return array(
    'farm_input_types' => array(
      'name' => 'Farm Input Types',
      'machine_name' => 'farm_input_types',
      'description' => 'A vocabulary of farm input types.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
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
    'farm_inputs' => array(
      'name' => 'Farm Inputs',
      'machine_name' => 'farm_inputs',
      'description' => 'A vocabulary of farm inputs.',
      'hierarchy' => '0',
      'module' => 'taxonomy',
      'weight' => '0',
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
