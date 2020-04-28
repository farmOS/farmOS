<?php
/**
 * @file
 * farm_trait.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_trait_strongarm() {
    $export = array();

    $strongarm = new stdClass();
    $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
    $strongarm->api_version = 1;
    $strongarm->name = 'pathauto_taxonomy_term_farm_trait_types_pattern';
    $strongarm->value = 'farm/trait/[term:name]';
    $export['pathauto_taxonomy_term_farm_trait_types_pattern'] = $strongarm;

    return $export;
}
