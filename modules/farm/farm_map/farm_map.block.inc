<?php

/**
 * @file
 * Farm map block code.
 */

/**
 * Implements hook_block_info().
 */
function farm_map_block_info() {
  $blocks['farm_map'] = array(
    'info' => t('Farm Map'),
    'cache' => DRUPAL_NO_CACHE,
  );
  return $blocks;
}

/**
 * Implements hook_block_view().
 */
function farm_map_block_view($delta = '') {
  $block = array();
  if ($delta == 'farm_map') {
    $block['subject'] = NULL;
    $block['content'] = farm_map_build('farm_map');
  }
  return $block;
}
