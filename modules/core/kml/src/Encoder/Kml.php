<?php

namespace Drupal\farm_kml\Encoder;

use Drupal\serialization\Encoder\XmlEncoder;

/**
 * Provides a KML encoder that extends from the XML encoder.
 *
 * @see \Symfony\Component\Serializer\Encoder\XmlEncoder
 */
class Kml extends XmlEncoder {

  /**
   * {@inheritdoc}
   */
  protected static $format = ['kml'];

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []) {

    // Build XML document to encode.
    $xml = [
      '@xmlns' => 'http://earth.google.com/kml/2.1',
      'Document' => [
        'Placemark' => $data,
      ],
    ];

    // Provide default context for the KML format.
    $xml_context = [
      'xml_version' => '1.0',
      'xml_encoding' => 'UTF-8',
      'xml_format_output' => TRUE,
      'xml_root_node_name' => 'kml',
    ] + $context;

    // Encode using the XML encoder.
    return $this->getBaseEncoder()->encode($xml, 'xml', $xml_context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format) {
    // @todo Implement decoding.
    return FALSE;
  }

}
