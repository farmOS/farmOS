<?php

namespace Drupal\data_stream\Plugin\FarmContext;

use Drupal\Core\StringTranslation\PluralTranslatableMarkup;
use Drupal\data_stream\Entity\DataStreamInterface;
use Drupal\farm_ui_context\Plugin\FarmContext\FarmContextBase;

/**
 * Provides context when an asset is associated with a data stream.
 *
 * @FarmContext(
 *   id = "data_stream_asset_farm_context",
 *   admin_label = @Translation("Data stream asset farm context"),
 *   context_definitions = {
 *     "asset" = @ContextDefinition("entity:asset", label = @Translation("Asset")),
 *   },
 * )
 */
class DataStreamAssetFarmContext extends FarmContextBase {

  /**
   * {@inheritdoc}
   */
  public function getMessages(): array {
    $messages = [];

    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    if ($asset = $this->getContextValue('asset')) {

      // Query for plans the asset might be a part of.
      $data_streams = \Drupal::entityTypeManager()->getStorage('data_stream')->loadByProperties([
        'asset' => $asset->id(),
      ]);

      // Bail if no data streams.
      $count = count($data_streams);
      if ($count === 0) {
        return $messages;
      }

      // Build a message summarizing the associated plans.
      $message = "Data Streams ($count)";

      $links = array_map(function (DataStreamInterface $data_stream) {
        return $data_stream->toLink()->toString();
      }, $data_streams);

      $long_message = new PluralTranslatableMarkup(
        $count,
        'This asset is associated with @count data stream.',
        'This asset is associated with @count data streams.',
      );
      $messages[] = [
        'type' => 'info',
        'message' => $message,
        'long_message' => $long_message,
        'links' => $links,
      ];
    }

    return $messages;
  }

}
