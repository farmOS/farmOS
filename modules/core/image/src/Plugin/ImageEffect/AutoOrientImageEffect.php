<?php

declare(strict_types=1);

namespace Drupal\farm_image\Plugin\ImageEffect;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Image\ImageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\Attribute\ImageEffect;
use Drupal\image\ImageEffectBase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Automatically rotates an image based on EXIF Orientation.
 */
#[ImageEffect(
  id: 'farm_image_auto_orient',
  label: new TranslatableMarkup('Auto-orient'),
  description: new TranslatableMarkup('Automatically rotates an image based on EXIF Orientation.')
)]
class AutoOrientImageEffect extends ImageEffectBase implements ContainerFactoryPluginInterface {

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * AutoOrientImageEffect constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, LoggerInterface $logger, FileSystemInterface $file_system) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('image'),
      $container->get('file_system'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function applyEffect(ImageInterface $image) {

    // Only proceed if the exif PHP extension is loaded.
    if (!extension_loaded('exif')) {
      return TRUE;
    }

    // Only work with supported image types.
    $mime_types = [
      'image/jpeg',
      'image/png',
      'image/tiff',
      'image/webp',
    ];
    if (!in_array($image->getMimeType(), $mime_types)) {
      return TRUE;
    }

    // Load EXIF orientation.
    $orientation = $this->loadExifOrientation($image->getSource());

    // If an orientation is not specified, bail.
    if (is_null($orientation)) {
      return TRUE;
    }

    // Apply transformations based on the EXIF Orientation value.
    // This logic is adapted from the Drupal Image Effects module.
    // @see https://git.drupalcode.org/project/image_effects/-/blob/730a1e6f5f947ef4d6eec5d359c6968ce93594f9/src/Plugin/ImageToolkit/Operation/gd/AutoOrient.php#L45-86
    // http://www.sno.phy.queensu.ca/~phil/exiftool/TagNames/EXIF.html:
    // 1 = Horizontal (normal)                 [top-left].
    // 2 = Mirror horizontal                   [top-right].
    // 3 = Rotate 180                          [bottom-right].
    // 4 = Mirror vertical                     [bottom-left].
    // 5 = Mirror horizontal and rotate 270 CW [left-top].
    // 6 = Rotate 90 CW                        [right-top].
    // 7 = Mirror horizontal and rotate 90 CW  [right-bottom].
    // 8 = Rotate 270 CW                       [left-bottom].
    switch ($orientation) {
      case 2:
        $result = $image->apply('mirror', ['x_axis' => TRUE]);
        break;

      case 3:
        $result = $image->apply('rotate', ['degrees' => 180]);
        break;

      case 4:
        $result = $image->apply('mirror', ['y_axis' => TRUE]);
        break;

      case 5:
        $result = $image->apply('mirror', ['x_axis' => TRUE]);
        if ($result) {
          $result = $image->apply('rotate', ['degrees' => 270]);
        }
        break;

      case 6:
        $result = $image->apply('rotate', ['degrees' => 90]);
        break;

      case 7:
        $result = $image->apply('mirror', ['x_axis' => TRUE]);
        if ($result) {
          $result = $image->apply('rotate', ['degrees' => 90]);
        }
        break;

      case 8:
        $result = $image->apply('rotate', ['degrees' => 270]);
        break;

      default:
        $result = TRUE;
    }

    // If the transformation failed, bail.
    if (!$result) {
      $this->logger->error('Image auto-orient failed using the %toolkit toolkit on %path (%mimetype)', [
        '%toolkit' => $image->getToolkitId(),
        '%path' => $image->getSource(),
        '%mimetype' => $image->getMimeType(),
      ]);
      return $result;
    }

    // Return the result.
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function transformDimensions(array &$dimensions, $uri) {

    // Only proceed if the exif PHP extension is loaded.
    if (!extension_loaded('exif')) {
      return;
    }

    // This logic is adapted from the Drupal Image Effects module.
    // @see https://git.drupalcode.org/project/image_effects/-/blob/730a1e6f5f947ef4d6eec5d359c6968ce93594f9/src/Plugin/ImageEffect/AutoOrientImageEffect.php
    $dimensions['width'] = $dimensions['width'] ? (int) $dimensions['width'] : NULL;
    $dimensions['height'] = $dimensions['height'] ? (int) $dimensions['height'] : NULL;
    if ($dimensions['width'] && $dimensions['height']) {
      $orientation = $this->loadExifOrientation($uri);
      if (in_array($orientation, [5, 6, 7, 8])) {
        $tmp = $dimensions['width'];
        $dimensions['width'] = $dimensions['height'];
        $dimensions['height'] = $tmp;
      }
    }
  }

  /**
   * Load EXIF Orientation value from an image file.
   *
   * @param string $uri
   *   The image file URI.
   *
   * @return int|null
   *   Returns an integer representing the EXIF Orientation value, or NULL if no
   *   Orientation key was found.
   */
  protected function loadExifOrientation(string $uri): ?int {

    // Get the image file path.
    $path = $this->fileSystem->realpath($uri);

    // If there is no path for the image, return NULL.
    if ($path === FALSE) {
      return NULL;
    }

    // Attempt to load EXIF data.
    $exif = @exif_read_data($path);

    // Return the Orientation value, if available.
    if (is_array($exif) && isset($exif['Orientation'])) {
      return $exif['Orientation'];
    }

    // Otherwise return NULL.
    return NULL;
  }

}
