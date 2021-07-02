<?php

namespace Drupal\farm_api\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Responds to the Kernel Response event to add CORS headers.
 *
 * CORS headers are only added to requests from the allowed origins configured
 * on consumer entities.
 */
class CorsResponseEventSubscriber implements EventSubscriberInterface {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new CorsResponseEventSubscriber.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['addCorsHeaders'];
    return $events;
  }

  /**
   * Adds CORS headers to the response.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The response event.
   */
  public function addCorsHeaders(ResponseEvent $event) {

    // Get the request headers.
    $request = $event->getRequest();
    $request_headers = $request->headers->all();

    // Bail if the request has no origin header.
    if (empty($request_headers['origin'])) {
      return;
    }
    $request_origin = reset($request_headers['origin']);

    // Load allowed_origins from all consumer entities.
    $consumers = $this->entityTypeManager->getStorage('consumer')->loadMultiple();
    $allowed_origins = array_reduce($consumers, function ($carry, $consumer) {
      /** @var \Drupal\Core\Field\FieldItemListInterface $list */
      $list = $consumer->get('allowed_origins');
      $list_values = array_map(function ($list_item) {
        return $list_item['value'] ? trim($list_item['value']) : NULL;
      }, $list->getValue());
      return array_merge($carry, $list_values);
    }, []);

    // Set the response headers if the request origin is allowed.
    if (in_array($request_origin, $allowed_origins)) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', $request_origin, TRUE);
      $response->headers->set('Access-Control-Allow-Credentials', 'true', TRUE);
      $response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Content-Disposition,Authorization,X-CSRF-Token', TRUE);
      $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,HEAD,OPTIONS', TRUE);
      $response->headers->set('Vary', 'Origin', TRUE);
    }
  }

}
