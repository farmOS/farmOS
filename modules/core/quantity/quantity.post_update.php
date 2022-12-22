<?php

/**
 * @file
 * Post update hooks for the quantity module.
 */

use Drupal\Core\Entity\Entity\EntityViewMode;

/**
 * Create plain text view mode for quantities.
 */
function quantity_post_update_plain_text_view_mode(&$sandbox) {
  $view_mode = EntityViewMode::create([
    'id' => 'quantity.plain_text',
    'label' => 'Plain text',
    'targetEntityType' => 'quantity',
    'cache' => FALSE,
    'dependencies' => [
      'enforced' => [
        'module' => [
          'quantity',
        ],
      ],
      'module' => [
        'quantity',
      ],
    ],
  ]);
  $view_mode->save();
}
