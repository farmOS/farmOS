<?php

declare(strict_types=1);

namespace Drupal\farm_timeline\Element;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element\RenderElementBase;

/**
 * Provides a farm timeline render element.
 *
 * @RenderElement("farm_timeline")
 */
class FarmTimeline extends RenderElementBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#theme' => 'farm_timeline',
      '#pre_render' => [
        [get_class($this), 'preRenderTimeline'],
      ],
      '#rows' => [],
    ];
  }

  /**
   * Pre-render callback for the timeline render array.
   *
   * @param array $element
   *   A renderable array for the timeline.
   *
   * @return array
   *   The final render array for the timeline.
   */
  public static function preRenderTimeline(array $element): array {

    // Set a timeline ID.
    if (empty($element['#attributes']['id'])) {
      $element['#attributes']['id'] = Html::getUniqueId('timeline');
    }

    // Set a blank header by default (otherwise it shows "undefined").
    $element['#attributes']['data-table-header'] = '';

    // Add timeline rows.
    $element['#attributes']['data-timeline-rows'] = Json::encode($element['#rows'] ?? []);

    // Add the farm-timeline class.
    $element['#attributes']['class'][] = 'farm-timeline';

    // Attach the farm_timeline library.
    $element['#attached']['library'][] = 'farm_timeline/farm_timeline';

    return $element;
  }

}
