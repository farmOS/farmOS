<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_kml\Kernel;

use Drupal\Component\Serialization\PhpSerialize;
use Drupal\KernelTests\KernelTestBase;
use Drupal\farm_geo\GeometryWrapper;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for KML deserialization.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class KmlTest extends KernelTestBase {

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_geo',
    'farm_kml',
    'geofield',
    'serialization',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->serializer = $this->container->get('serializer');
  }

  /**
   * Test that placemark name and description are deserialized as strings.
   */
  public function testPlacemarkProperties() {
    $placemarks = [
      'plain text' => [
        'kml' => '<name>Plain</name><description>Plain description</description>',
        'name' => 'Plain',
        'description' => 'Plain description',
      ],
      'CDATA in description' => [
        'kml' => '<name>CDATA description</name><description><![CDATA[<h4>Layer: Layer2</h4><p>Fix Quality: DGPS</p>]]></description>',
        'name' => 'CDATA description',
        'description' => '<h4>Layer: Layer2</h4><p>Fix Quality: DGPS</p>',
      ],
      'CDATA in name' => [
        'kml' => '<name><![CDATA[Field <b>A</b>]]></name><description>CDATA name</description>',
        'name' => 'Field <b>A</b>',
        'description' => 'CDATA name',
      ],
      'CDATA with surrounding whitespace' => [
        'kml' => '<name>Whitespace</name><description> <![CDATA[Indented]]> </description>',
        'name' => 'Whitespace',
        'description' => ' Indented ',
      ],
      'adjacent CDATA sections' => [
        'kml' => '<name>Adjacent</name><description><![CDATA[<p>one</p>]]><![CDATA[<p>two</p>]]></description>',
        'name' => 'Adjacent',
        'description' => '<p>one</p><p>two</p>',
      ],
      'CDATA mixed with text' => [
        'kml' => '<name>Mixed</name><description>before <![CDATA[<i>middle</i>]]> after</description>',
        'name' => 'Mixed',
        'description' => 'before <i>middle</i> after',
      ],
      'empty CDATA' => [
        'kml' => '<name>Empty CDATA</name><description><![CDATA[]]></description>',
        'name' => 'Empty CDATA',
        'description' => '',
      ],
      'empty description' => [
        'kml' => '<name>Empty description</name><description></description>',
        'name' => 'Empty description',
        'description' => '',
      ],
      // An element only keeps the text that is directly inside it. Text that
      // is inside child elements is dropped. This is a known limitation. All
      // supported properties are plain scalar values in practice.
      'nested child elements' => [
        'kml' => '<name>Nested</name><description>Hello <b>world</b> and more</description>',
        'name' => 'Nested',
        // The text of the <b> element is dropped. Two spaces remain.
        'description' => 'Hello  and more',
      ],
      'nested child element only' => [
        'kml' => '<name>Nested only</name><description><b>world</b></description>',
        'name' => 'Nested only',
        // The element holds no direct text, so the result is an empty string.
        'description' => '',
      ],
      'nested child elements in name' => [
        'kml' => '<name>Field <b>A</b> north</name><description>Nested name</description>',
        'name' => 'Field  north',
        'description' => 'Nested name',
      ],
    ];

    foreach ($placemarks as $label => $placemark) {
      $kml = '<?xml version="1.0" encoding="UTF-8"?>'
        . '<kml xmlns="http://www.opengis.net/kml/2.2"><Document><Placemark>'
        . $placemark['kml']
        . '<Point><coordinates>-97.64455901341667,30.530107166625,179.72090000000003</coordinates></Point>'
        . '</Placemark></Document></kml>';

      /** @var \Drupal\farm_geo\GeometryWrapper[] $geometries */
      $geometries = $this->serializer->deserialize($kml, GeometryWrapper::class, 'geometry_kml');
      $this->assertCount(1, $geometries, $label);

      // Confirm that the geometry was parsed.
      $this->assertEquals('POINT (-97.64455901341667 30.530107166625)', $geometries[0]->geometry->out('wkt'), $label);

      // Confirm that the name and description are strings with the expected
      // values. CDATA content must be preserved, not dropped.
      $this->assertIsString($geometries[0]->properties['name'], $label);
      $this->assertIsString($geometries[0]->properties['description'], $label);
      $this->assertEquals($placemark['name'], $geometries[0]->properties['name'], $label);
      $this->assertEquals($placemark['description'], $geometries[0]->properties['description'], $label);

      // Confirm that the properties can be serialized.
      // @see \Drupal\farm_import_kml\Form\KmlImporter
      $serialized = PhpSerialize::encode($geometries[0]->properties);
      $this->assertEquals($geometries[0]->properties, PhpSerialize::decode($serialized), $label);
    }
  }

}
