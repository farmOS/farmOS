<?php

namespace Drupal\farm_kml\Plugin\Action;

use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Action that exports KML from an entity geofield.
 *
 * @Action(
 *   id = "entity:kml_action",
 *   action_label = @Translation("Export entity geometry as KML"),
 *   deriver = "Drupal\farm_kml\Plugin\Action\Derivative\EntityKmlDeriver",
 * )
 */
class EntityKml extends EntityActionBase {

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The GeoPHP service.
   *
   * @var \Drupal\geofield\GeoPHP\GeoPHPInterface
   */
  protected $geoPHP;

  /**
   * The default file scheme.
   *
   * @var string
   */
  protected $defaultFileScheme;

  /**
   * Constructs a new EntityKml object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   The serializer service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   * @param \Drupal\geofield\GeoPHP\GeoPHPInterface $geo_PHP
   *   The GeoPHP service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, SerializerInterface $serializer, FileSystemInterface $file_system, GeoPHPInterface $geo_PHP, ConfigFactoryInterface $config_factory) {
    $this->serializer = $serializer;
    $this->fileSystem = $file_system;
    $this->geoPHP = $geo_PHP;
    $this->defaultFileScheme = $config_factory->get('system.file')->get('default_scheme') ?? 'public';
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('serializer'),
      $container->get('file_system'),
      $container->get('geofield.geophp'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $entities) {

    // Bail if no geofield field is provided.
    if (empty($this->configuration['geofield'])) {
      return;
    }

    // Collect geometries as placemark definitions.
    $geofield = $this->configuration['geofield'];
    $placemarks = [];
    foreach ($entities as $entity) {

      // If the entity doesn't have the configured geofield field, bail.
      if (!$entity->hasField($geofield)) {
        continue;
      }

      $field_value = $entity->get($geofield)->first();
      $wkt = $field_value->get('value')->getValue();
      if (!empty($wkt)) {

        // Convert WKT to KML string.
        $geometry = $this->geoPHP->load($wkt, 'wkt');
        $kml_string = $geometry->out('kml');

        // Parse the KML string into an XML object.
        // This is necessary so that we can encode the KML into XML with the
        // rest of the asset data.
        $kml = simplexml_load_string($kml_string);
        $kml_name = $kml->getName();
        $kml_value = $kml->children();

        // Build a placemark definition.
        $placemark = [
          '@id' => $entity->getEntityTypeId() . '-' . $entity->id(),
          '#' => [
            'name' => htmlspecialchars($entity->label()),
            $kml_name => $kml_value,
          ],
        ];

        // Add entity notes.
        if ($entity->hasField('notes')) {
          $notes = $entity->get('notes')->first()->getValue();
          if (!empty($notes['value'])) {
            $placemark['#']['description'] = $notes['value'];
          }
        }

        $placemarks[] = $placemark;
      }
    }

    // If there are no placemarks, bail with a warning.
    if (empty($placemarks)) {
      $this->messenger()->addWarning($this->t('No placemarks were found.'));
      return;
    }

    // Build XML document to encode.
    $xml = [
      '@xmlns' => 'http://earth.google.com/kml/2.1',
      'Document' => [
        'Placemark' => $placemarks,
      ],
    ];
    $xml_context = [
      'xml_version' => '1.0',
      'xml_encoding' => 'UTF-8',
      'xml_format_output' => TRUE,
      'xml_root_node_name' => 'kml',
    ];
    $output = $this->serializer->encode($xml, 'xml', $xml_context);

    // Prepare the file directory.
    $directory = $this->defaultFileScheme . '://kml';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Create the file.
    $filename = 'kml_export-' . date('c') . '.kml';
    $destination = "$directory/$filename";
    $file = file_save_data($output, $destination);

    // If file creation failed, bail with a warning.
    if (empty($file)) {
      $this->messenger()->addWarning($this->t('Could not create file.'));
      return;
    }

    // Make the file temporary.
    $file->status = 0;
    $file->save();

    // Show a link to the file.
    $url = file_create_url($file->getFileUri());
    $this->messenger()->addMessage($this->t('KML file created: <a href=":url">%filename</a>', [
      ':url' => $url,
      '%filename' => $file->label(),
    ]));
  }

  /**
   * {@inheritdoc}
   */
  public function execute($object = NULL) {
    $this->executeMultiple([$object]);
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return $object->access('view', $account, $return_as_object);
  }

}
