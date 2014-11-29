<?php
/**
 * @file
 * farm_soil.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_soil_taxonomy_default_vocabularies() {
  return array(
    'farm_soil_types' => array(
      'name' => 'Farm Soil Types',
      'machine_name' => 'farm_soil_types',
      'description' => 'A vocabulary of soil types.',
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
