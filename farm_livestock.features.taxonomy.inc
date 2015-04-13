<?php
/**
 * @file
 * Taxonomy features for farm_livestock.
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_livestock_taxonomy_default_vocabularies() {
  return array(
    'farm_animal_groups' => array(
      'name' => 'Farm Animal Groups',
      'machine_name' => 'farm_animal_groups',
      'description' => 'Vocabulary of farm animal groups, herds, flocks, etc.',
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
    'farm_animal_types' => array(
      'name' => 'Farm Animal Types',
      'machine_name' => 'farm_animal_types',
      'description' => 'A vocabulary of farm animal types.',
      'hierarchy' => '1',
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
