<?php

declare(strict_types=1);

namespace Drupal\data_stream\Hook;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\data_stream\Entity\DataStreamInterface;

/**
 * Theme hook implementations for data_stream.
 */
class ThemeHooks {

  use StringTranslationTrait;

  public function __construct(
    protected TimeInterface $time,
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_view_alter().
   */
  #[Hook('data_stream_view_alter')]
  public function dataStreamViewAlter(array &$build, DataStreamInterface $data_stream, EntityViewDisplayInterface $display) {

    // Bail if not the basic type.
    if ($data_stream->bundle() != 'basic') {
      return;
    }

    // If the user has permission to edit this sensor asset, display developer
    // information.
    // Only render developer information in the full view mode.
    // Use getOriginalMode() because getMode() is not reliable. The default
    // display mode will return "full" unless a "default" display is actually
    // saved in config. In either case, the original mode is always "full".
    if ($data_stream->access('update') === TRUE && $display->getOriginalMode() === 'full') {

      // Add a Developer information details element with brief description.
      // @todo Include link to updated user guide.
      $build['api'] = [
        '#type' => 'details',
        '#title' => $this->t('Developer information'),
        '#description' => $this->t('This data stream type will listen for data posted to it from other web-connected devices. Use the information below to configure your device to begin posting data to this data stream.'),
        '#open' => FALSE,
      ];

      // Build URL to the data stream API endpoint.
      $url = new Url('data_stream.data', ['uuid' => $data_stream->uuid()]);

      // If the data stream is not public, include the private key.
      if (!$data_stream->isPublic()) {
        $url->setOption('query', ['private_key' => $data_stream->getPrivateKey()]);
      }

      // Render the API url.
      $url_string = $url->setAbsolute()->toString();
      $url_string_label = $this->t('URL');
      $build['api']['url'] = [
        '#type' => 'link',
        '#title' => $url_string,
        '#url' => $url,
        '#prefix' => '<p><strong>' . $url_string_label . ':</strong> ',
        '#suffix' => '</p>',
      ];

      // Render JSON examples.
      $request_time = $this->time->getRequestTime();
      $stream_name = Html::escape($data_stream->label());
      $json_example = '{ "timestamp": ' . $request_time . ', "' . $stream_name . '": 76.5 }';
      $json_example_label = $this->t('JSON Example');
      $build['api']['json_example'] = [
        '#markup' => '<p><strong>' . $json_example_label . ':</strong> ' . $json_example . '</p>',
      ];

      // Render example CURL command.
      $curl_example = 'curl -H "Content-Type: application/json" -X POST -d \'' . $json_example . '\' ' . $url_string;
      $curl_example_label = $this->t('Example CURL command');
      $build['api']['curl_example'] = [
        '#markup' => '<p><strong>' . $curl_example_label . ':</strong> ' . $curl_example . '</p>',
      ];
    }

    // Add the basic data block view.
    $build['views']['data'] = [
      '#type' => 'view',
      '#name' => 'data_stream_basic_data',
      '#display_id' => 'block',
      '#arguments' => [$data_stream->id()],
    ];
  }

}
