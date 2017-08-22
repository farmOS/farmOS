<?php
/**
 * @file
 * farm_season.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_season_taxonomy_default_vocabularies() {
  return array(
    'farm_season' => array(
      'name' => 'Farm Season',
      'machine_name' => 'farm_season',
      'description' => 'A list of farm seasons.',
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
