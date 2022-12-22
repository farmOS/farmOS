<?php

namespace Drupal\quantity;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Quantity entity view builder.
 */
class QuantityViewBuilder extends EntityViewBuilder implements TrustedCallbackInterface {

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $entity, $view_mode = 'full', $langcode = NULL) {
    $build = parent::view($entity, $view_mode, $langcode);

    // If the view mode is plain_text, add a post render callback to strip tags
    // and whitespace.
    if ($view_mode == 'plain_text') {
      $build['#post_render'][] = [$this, 'plainText'];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return array_merge(parent::trustedCallbacks(), ['plainText']);
  }

  /**
   * Strips HTML, newlines, and whitespace from rendered entity.
   *
   * @param string $value
   *   A string of rendered content for the Quantity entity.
   *
   * @return string
   *   The updated content.
   */
  public static function plainText(string $value) {
    $value = Html::decodeEntities($value);
    $value = strip_tags($value);
    $value = trim($value);
    $value = preg_replace('/\s+/', ' ', $value);
    return $value;
  }

}
