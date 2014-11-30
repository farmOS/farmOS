<?php
/**
 * @file
 * farm_crop.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function farm_crop_taxonomy_default_vocabularies() {
  return array(
    'farm_crop_families' => array(
      'name' => 'Farm Crop Families',
      'machine_name' => 'farm_crop_families',
      'description' => 'A taxonomy of crop families, for organizing crops.',
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
    'farm_crops' => array(
      'name' => 'Farm Crops',
      'machine_name' => 'farm_crops',
      'description' => 'A taxonomy of crop types and varieties.',
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
