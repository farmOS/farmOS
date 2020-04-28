<?php
/**
 * @file
 * farm_trait.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_trait_taxonomy_default_vocabularies() {
  return array(
    'farm_trait_types' => array(
      'name' => 'Farm Trait Types',
      'machine_name' => 'farm_trait_types',
      'description' => 'A vocabulary of trait types.',
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
