<?php

namespace Drupal\farm_export_kml\Plugin\Action;

use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\file\FileRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Action that exports KML from an entity geofield.
 *
 * @Action(
 *   id = "entity:kml_action",
 *   action_label = @Translation("Export entity geometry as KML"),
 *   deriver = "Drupal\farm_export_kml\Plugin\Action\Derivative\EntityKmlDeriver",
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
   * The default file scheme.
   *
   * @var string
   */
  protected $defaultFileScheme;

  /**
   * The file repository service.
   *
   * @var \Drupal\file\FileRepositoryInterface
   */
  protected $fileRepository;

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

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
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\file\FileRepositoryInterface $file_repository
   *   The file repository service.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, SerializerInterface $serializer, FileSystemInterface $file_system, ConfigFactoryInterface $config_factory, FileRepositoryInterface $file_repository, FileUrlGeneratorInterface $file_url_generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager);
    $this->serializer = $serializer;
    $this->fileSystem = $file_system;
    $this->defaultFileScheme = $config_factory->get('system.file')->get('default_scheme') ?? 'public';
    $this->fileRepository = $file_repository;
    $this->fileUrlGenerator = $file_url_generator;
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
      $container->get('config.factory'),
      $container->get('file.repository'),
      $container->get('file_url_generator'),
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

    // Serialize the entities using the specified geofield name.
    $context = ['geofield' => $this->configuration['geofield']];
    $output = $this->serializer->serialize($entities, 'geometry_kml', $context);

    // If there are no placemarks, bail with a warning.
    $kml = simplexml_load_string($output);
    if (empty($kml->Document->Placemark) || empty($kml->Document->Placemark->count())) {
      $this->messenger()->addWarning($this->t('No placemarks were found.'));
      return;
    }

    // Prepare the file directory.
    $directory = $this->defaultFileScheme . '://kml';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Create the file.
    $filename = 'kml_export-' . date('c') . '.kml';
    $destination = "$directory/$filename";
    try {
      $file = $this->fileRepository->writeData($output, $destination);
    }

    // If file creation failed, bail with a warning.
    catch (\Exception $e) {
      $this->messenger()->addWarning($this->t('Could not create file.'));
      return;
    }

    // Make the file temporary.
    $file->status = 0;
    $file->save();

    // Show a link to the file.
    $url = $this->fileUrlGenerator->generateAbsoluteString($file->getFileUri());
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
