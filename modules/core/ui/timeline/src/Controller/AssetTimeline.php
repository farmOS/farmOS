<?php

namespace Drupal\farm_ui_timeline\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\farm_timeline\Controller\TimelineControllerBase;
use Drupal\farm_timeline\TypedData\TimelineRowDefinition;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Asset timeline controller.
 */
class AssetTimeline extends TimelineControllerBase {

  /**
   * API endpoint for asset timeline.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset entity.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Json response of timeline data.
   */
  public function data(AssetInterface $asset) {
    $data = [];

    $row_values = [
      'id' => 'asset--' . $asset->id(),
      'label' => $asset->label(),
    ];

    // Add tasks for all logs that reference the asset.
    $row_values['tasks'] = array_map(function (LogInterface $log) use ($asset) {
      return $this->buildLogTask($log, $asset->toUrl());
    }, $this->assetLogs->getLogs($asset));

    // Add the row object.
    // @todo Create and instantiate a wrapper data type instead of rows.
    $row_definition = TimelineRowDefinition::create('farm_timeline_row');
    $data['rows'][] = $this->typedDataManager->create($row_definition, $row_values);

    // Serialize to JSON and return response.
    $serialized = $this->serializer->serialize($data, 'json');
    return new JsonResponse($serialized, 200, [], TRUE);
  }

}
