<?php

namespace Drupal\farm_import_kml\Form;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_geo\GeometryWrapper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Provides a form for importing KML placemarks as land assets.
 *
 * @todo Allow placemarks to be imported as any asset type.
 * This is challenging because some asset type bundles have required fields
 * like the "plant type" and "animal type".
 * @see https://www.drupal.org/project/farm/issues/3230970
 */
class KmlImporter extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
   * Constructs a new KmlImporter object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   The serializer service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, SerializerInterface $serializer, FileSystemInterface $file_system) {
    $this->entityTypeManager = $entity_type_manager;
    $this->serializer = $serializer;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('serializer'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_kml_import_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['input'] = [
      '#type' => 'details',
      '#title' => $this->t('Input'),
      '#open' => TRUE,
    ];

    $form['input']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('KML File'),
      '#description' => $this->t('Upload your KML file here and click "Parse".'),
      '#upload_location' => 'private://kml',
      '#upload_validators' => [
        'file_validate_extensions' => ['kml kmz'],
      ],
      '#required' => TRUE,
    ];

    // Build land type options.
    $land_type_options = farm_land_type_options();
    $form['input']['land_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Default land type'),
      '#description' => $this->t('Specify the default land type for the assets in this KML. This can be overridden below on a per-asset basis before creating the assets.'),
      '#options' => $land_type_options,
      '#required' => TRUE,
    ];

    $form['input']['parse'] = [
      '#type' => 'button',
      '#value' => $this->t('Parse'),
      '#ajax' => [
        'callback' => '::parseKml',
        'wrapper' => 'output',
      ],
    ];

    // Hidden field to track if the file was parsed. This helps with validation.
    $form['input']['parsed'] = [
      '#type' => 'hidden',
      '#value' => FALSE,
    ];

    $form['output'] = [
      '#type' => 'container',
      '#prefix' => '<div id="output">',
      '#suffix' => '</div>',
    ];

    // Only generate the output if a file and land type have been selected.
    // Uploading a file will trigger an ajax call, but the land type won't
    // be set in the form state until the user selects "Parse".
    $file_ids = $form_state->getValue('file', []);
    $land_type = $form_state->getValue('land_type');
    if (empty($file_ids) || empty($land_type)) {
      return $form;
    }

    // Get the uploaded file contents.
    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityTypeManager->getStorage('file')->load(reset($file_ids));
    $path = $file->getFileUri();
    if ($file->getMimeType() === 'application/vnd.google-earth.kmz' && extension_loaded('zip')) {
      $path = 'zip://' . $this->fileSystem->realpath($path) . '#doc.kml';
    }
    $data = file_get_contents($path);

    // Deserialize the KML placemarks into WKT geometry.
    /** @var \Drupal\farm_geo\GeometryWrapper[] $geometries */
    $geometries = $this->serializer->deserialize($data, GeometryWrapper::class, 'geometry_kml');

    // Bail if no geometries were found.
    if (empty($geometries)) {
      $this->messenger()->addWarning($this->t('No placemarks could be parsed from the uploaded file.'));
      return $form;
    }

    // Display the output details.
    $form['output']['#type'] = 'details';
    $form['output']['#title'] = $this->t('Output');
    $form['output']['#open'] = TRUE;

    // Build a tree for asset data.
    $form['output']['assets'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];
    foreach ($geometries as $index => $geometry) {

      // Create a fieldset for the geometry.
      $form['output']['assets'][$index] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Geometry') . ' ' . ($index + 1),
      ];

      $form['output']['assets'][$index]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
        '#default_value' => $geometry->properties['name'] ?? '',
      ];

      $form['output']['assets'][$index]['land_type'] = [
        '#type' => 'select',
        '#title' => $this->t('Land type'),
        '#options' => $land_type_options,
        '#default_value' => $form_state->getValue('land_type'),
        '#required' => TRUE,
      ];

      $form['output']['assets'][$index]['notes'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Notes'),
        '#default_value' => $geometry->properties['description'] ?? '',
      ];

      $form['output']['assets'][$index]['geometry'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Geometry'),
        '#default_value' => $geometry->geometry->out('wkt'),
      ];

      $form['output']['assets'][$index]['confirm'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create this asset'),
        '#description' => $this->t('Uncheck this if you do not want to create this asset in farmOS.'),
        '#default_value' => TRUE,
      ];
    }

    // Mark the form as parsed.
    $form['input']['parsed']['#value'] = TRUE;

    // Fields for creating a parent asset.
    $form['output']['parent'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];

    $form['output']['parent']['create'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create a parent asset'),
      '#description' => $this->t('Optionally create a parent asset and all geometries above will be added as child assets of it. This is helpful if you are creating a lot of assets, and want to keep them all organized upon import.'),
      '#default_value' => FALSE,
    ];

    $form['output']['parent']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#states' => [
        'visible' => [
          ':input[name="parent[create]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['output']['parent']['land_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Land type'),
      '#options' => $land_type_options,
      '#default_value' => $form_state->getValue('land_type'),
      '#states' => [
        'visible' => [
          ':input[name="parent[create]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['output']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create assets'),
    ];

    return $form;
  }

  /**
   * Ajax callback that returns the output fieldset after parsing KML.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return mixed
   *   The elements to replace.
   */
  public function parseKml(array &$form, FormStateInterface $form_state) {
    return $form['output'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Only validate if the file has been parsed.
    if (!$form_state->getValue('parsed')) {
      return;
    }

    $assets = $form_state->getValue('assets', []);
    $confirmed_assets = array_filter($assets, function ($asset) {
      return !empty($asset['confirm']);
    });

    // Set an error if no assets are selected to be created.
    if (empty($confirmed_assets)) {
      $form_state->setErrorByName('submit', $this->t('At least one asset must be created.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Bail if no file was uploaded.
    $file_ids = $form_state->getValue('file', []);
    if (empty($file_ids)) {
      $this->messenger()->addError($this->t('File upload failed.'));
      return;
    }

    // Load the assets to create.
    $assets = $form_state->getValue('assets', []);
    $confirmed_assets = array_filter($assets, function ($asset) {
      return !empty($asset['confirm']);
    });

    // Create a parent asset if specified.
    $parent = $form_state->getValue('parent');
    $parent_asset = NULL;
    if (!empty($parent['create'])) {
      $parent_asset = Asset::create([
        'type' => 'land',
        'land_type' => $parent['land_type'],
        'is_location' => TRUE,
        'is_fixed' => TRUE,
        'name' => $parent['name'],
      ]);
      $parent_asset->save();
      $asset_url = $parent_asset->toUrl()->setAbsolute()->toString();
      $this->messenger()->addMEssage($this->t('Created land asset: <a href=":url">%asset_label</a>', [':url' => $asset_url, '%asset_label' => $parent_asset->label()]));
    }

    // Create new assets.
    foreach ($confirmed_assets as $asset) {
      $new_asset = Asset::create([
        'type' => 'land',
        'land_type' => $asset['land_type'],
        'intrinsic_geometry' => $asset['geometry'],
        'is_location' => TRUE,
        'is_fixed' => TRUE,
      ]);

      // Set the name.
      if (!empty($asset['name'])) {
        $new_asset->set('name', $asset['name']);
      }

      // Set the notes.
      if (!empty($asset['notes'])) {
        $new_asset->set('notes', $asset['notes']);
      }

      // Set the parent.
      if (!empty($parent_asset)) {
        $new_asset->set('parent', $parent_asset);
      }

      $new_asset->save();
      $asset_url = $new_asset->toUrl()->setAbsolute()->toString();
      $this->messenger()->addMEssage($this->t('Created land asset: <a href=":url">%asset_label</a>', [':url' => $asset_url, '%asset_label' => $new_asset->label()]));
    }
  }

}
