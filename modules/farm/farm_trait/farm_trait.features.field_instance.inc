<?php
/**
 * @file
 * farm_trait.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_trait_field_default_field_instances() {
    $field_instances = array();

    // Exported field_instance: 'field_collection_item-farm_trait-field_farm_trait_type'.
    $field_instances['field_collection_item-farm_trait-field_farm_trait_type'] = array(
        'bundle' => 'field_farm_trait',
        'default_value' => NULL,
        'deleted' => 0,
        'description' => 'Enter the trait type. As you type, you will have the option of selecting from traits you have entered in the past.',
        'display' => array(
            'default' => array(
                'label' => 'inline',
                'module' => 'taxonomy',
                'settings' => array(),
                'type' => 'taxonomy_term_reference_link',
                'weight' => 1,
            ),
        ),
        'entity_type' => 'field_collection_item',
        'field_name' => 'field_farm_trait_type',
        'label' => 'Trait type',
        'required' => 1,
        'settings' => array(
            'user_register_form' => FALSE,
        ),
        'widget' => array(
            'active' => 0,
            'module' => 'taxonomy',
            'settings' => array(
                'autocomplete_path' => 'taxonomy/autocomplete',
                'size' => 60,
            ),
            'type' => 'taxonomy_autocomplete',
            'weight' => 1,
        ),
    );

    // Exported field_instance: 'field_collection_item-farm_trait-field_farm_trait_value'.
    $field_instances['field_collection_item-farm_trait-field_farm_trait_value'] = array(
        'bundle' => 'field_farm_trait',
        'default_value' => NULL,
        'deleted' => 0,
        'description' => '',
        'display' => array(
            'default' => array(
                'label' => 'inline',
                'module' => 'text',
                'settings' => array(),
                'type' => 'text_default',
                'weight' => 3,
            ),
        ),
        'entity_type' => 'field_collection_item',
        'field_name' => 'field_farm_trait_value',
        'label' => 'Value',
        'required' => 1,
        'settings' => array(
            'text_processing' => 0,
            'user_register_form' => FALSE,
        ),
        'widget' => array(
            'active' => 1,
            'module' => 'text',
            'settings' => array(
                'size' => 60,
            ),
            'type' => 'text_textfield',
            'weight' => 3,
        ),
    );

    // Exported field_instance: 'taxonomy_term-farm_trait-field_farm_trait_type_ref_id'.
    $field_instances['taxonomy_term-farm_trait-field_farm_trait_type_ref_id'] = array(
        'bundle' => 'farm_trait_types',
        'default_value' => array(),
        'deleted' => 0,
        'description' => 'If this trait type is part of a standard ontology (see <a href="http://www.obofoundry.org/">obofoundry.org/</a>), include reference its identifier.',
        'display' => array(
            'default' => array(
                'label' => 'inline',
                'module' => 'text',
                'settings' => array(),
                'type' => 'text_default',
                'weight' => 3,
            ),
        ),
        'entity_type' => 'taxonomy_term',
        'field_name' => 'field_farm_trait_type_ref_id',
        'label' => 'Reference ID',
        'required' => 0,
        'settings' => array(
            'text_processing' => 0,
            'user_register_form' => FALSE,
        ),
        'widget' => array(
            'active' => 1,
            'module' => 'text',
            'settings' => array(
                'size' => 60,
            ),
            'type' => 'text_textfield',
            'weight' => 3,
        ),
    );

    // Exported field_instance: 'taxonomy_term-farm_trait-field_farm_trait_type_desc'.
    $field_instances['taxonomy_term-farm_trait-field_farm_trait_type_desc'] = array(
        'bundle' => 'farm_trait_types',
        'default_value' => array(),
        'deleted' => 0,
        'description' => '',
        'display' => array(
            'default' => array(
                'label' => 'inline',
                'module' => 'text',
                'settings' => array(),
                'type' => 'text_default',
                'weight' => 3,
            ),
        ),
        'entity_type' => 'taxonomy_term',
        'field_name' => 'field_farm_trait_type_desc',
        'label' => 'Description',
        'required' => 0,
        'settings' => array(
            'text_processing' => 0,
            'user_register_form' => FALSE,
        ),
        'widget' => array(
            'active' => 1,
            'module' => 'text',
            'settings' => array(
                'size' => 60,
            ),
            'type' => 'text_textfield',
            'weight' => 4,
        ),
    );

    // Translatables
    // Included for use with string extractors like potx.
    t('Trait ID');
    t('Value');
    t('Reference ID');
    t('Description');

    return $field_instances;
}
