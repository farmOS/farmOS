<?php

namespace Drupal\farm_fieldkit\Controller;

use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\farm_fieldkit\Entity\FieldModuleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Serves JavaScript files for field modules.
 */
class FieldModuleController extends ControllerBase {

  /**
   * The library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * FieldModuleController constructor.
   *
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $library_discovery
   *   The library discovery service.
   */
  public function __construct(LibraryDiscoveryInterface $library_discovery) {
    $this->libraryDiscovery = $library_discovery;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('library.discovery')
    );
  }

  /**
   * Returns the FieldModule JS.
   *
   * @param \Drupal\farm_fieldkit\Entity\FieldModuleInterface $field_module
   *   The FieldtModule config entity.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   A response containing the FieldModule JS or a 422 error response.
   */
  public function index(FieldModuleInterface $field_module) {

    // Get the field module library.
    $library = $field_module->getLibrary();
    [$extension, $name] = explode('/', $library, 2);
    $definition = $this->libraryDiscovery->getLibraryByName($extension, $name);

    // Bail if no JS library is provided.
    if (empty($definition['js'])) {
      throw new UnprocessableEntityHttpException('The field module does not have a valid JS library configured.');
    }

    // Try loading the raw JS data.
    $raw = file_get_contents($definition['js'][0]['data']);
    if (empty($raw)) {
      throw new UnprocessableEntityHttpException('The field module JS library could not be loaded.');
    }

    // Return a response with the JS asset.
    $response = new Response();
    $response->headers->set('Content-Type', 'application/javascript; utf-8');
    $response->setContent($raw);
    return $response;
  }

}
