<?php

namespace Drupal\farm_client\Controller;

use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\farm_client\Entity\ClientModuleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Serves JavaScript files for client modules.
 */
class ClientModuleController extends ControllerBase {

  /**
   * The library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * ClientModuleController constructor.
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
   * Returns the ClientModule JS.
   *
   * @param \Drupal\farm_client\Entity\ClientModuleInterface $client_module
   *   The ClientModule config entity.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   A response containing the ClientModule JS or a 422 error response.
   */
  public function index(ClientModuleInterface $client_module) {

    // Get the client module library.
    $library = $client_module->getLibrary();
    [$extension, $name] = explode('/', $library, 2);
    $definition = $this->libraryDiscovery->getLibraryByName($extension, $name);

    // Bail if no JS library is provided.
    if (empty($definition['js'])) {
      throw new UnprocessableEntityHttpException('The client module does not have a valid JS library configured.');
    }

    // Try loading the raw JS data.
    $raw = file_get_contents($definition['js'][0]['data']);
    if (empty($raw)) {
      throw new UnprocessableEntityHttpException('The client module JS library could not be loaded.');
    }

    // Return a response with the JS asset.
    $response = new Response();
    $response->headers->set('Content-Type', 'application/javascript; utf-8');
    $response->setContent($raw);
    return $response;
  }

}
