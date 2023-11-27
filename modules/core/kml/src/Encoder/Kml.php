<?php

namespace Drupal\farm_kml\Encoder;

use Drupal\serialization\Encoder\XmlEncoder;

/**
 * Provides a KML encoder that extends from the XML encoder.
 *
 * An array of Placemark data prepared for XmlEncoder is expected when encoding.
 *
 * When decoding, the array of Placemark data is returned with an additional
 * "xml" key containing the individual placemark XML in a string.
 *
 * @see \Symfony\Component\Serializer\Encoder\XmlEncoder
 *
 * @phpstan-ignore-next-line
 */
class Kml extends XmlEncoder {

  /**
   * {@inheritdoc}
   */
  protected static $format = ['geometry_kml'];

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []): string {

    // Build XML document to encode.
    $xml = [
      '@xmlns' => 'http://earth.google.com/kml/2.1',
      'Document' => [
        'Placemark' => array_filter(array_values($data)),
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
  public function decode($data, $format, array $context = []): mixed {

    // Start an array of decoded placemark data.
    $decoded_placemarks = [];

    // Build an XML object.
    $xml = simplexml_load_string($data);

    // If empty, or failed to parse, bail.
    if (empty($xml)) {
      return $decoded_placemarks;
    }

    // Determine the root. Sometimes it is "Document".
    $root = $xml;
    if (isset($xml->Document)) {
      $root = $xml->Document;
    }

    // Start an array of placemarks to decode.
    $placemarks = [];

    // If the KML is organized into folders, iterate through them.
    if (isset($root->Folder)) {
      foreach ($root->Folder as $folder) {
        if (isset($folder->Placemark)) {
          foreach ($folder->Placemark as $placemark) {
            $placemarks[] = $placemark;
          }
        }
      }
    }

    // Also check the root for any placemarks.
    if (isset($root->Placemark)) {
      foreach ($root->Placemark as $placemark) {
        $placemarks[] = $placemark;
      }
    }

    // Decode each placemark into an array.
    // Include the individual placemark as an XML string.
    foreach ($placemarks as $placemark) {
      $geometry = (array) $placemark;
      $geometry['xml'] = $placemark->asXML();
      $decoded_placemarks[] = $geometry;
    }

    return $decoded_placemarks;
  }

}
