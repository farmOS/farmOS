<?php

namespace Drupal\data_stream_notification\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\data_stream_notification\Entity\DataStreamNotificationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for notification UI routes.
 *
 * @see \Drupal\views_ui\Controller\ViewsUIController::ajaxOperation()
 */
class NotificationUIController extends ControllerBase {

  /**
   * Calls a method on a data stream notification and reloads the listing page.
   *
   * @param \Drupal\data_stream_notification\Entity\DataStreamNotificationInterface $data_stream_notification
   *   The data stream notification entity.
   * @param string $op
   *   The operation to perform, e.g., 'enable' or 'disable'.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   Either returns a rebuilt listing page as an AJAX response, or redirects
   *   back to the listing page.
   */
  public function ajaxOperation(DataStreamNotificationInterface $data_stream_notification, string $op, Request $request) {
    // Perform the operation.
    $data_stream_notification->$op()->save();

    // Reset the notification state.
    $data_stream_notification->resetState();

    // If the request is via AJAX, return the rendered list as JSON.
    if ($request->request->get('js')) {
      $list = $this->entityTypeManager()
        ->getListBuilder('data_stream_notification')
        ->render();
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#data-stream-notification-entity-list', $list));
      return $response;
    }

    // Otherwise, redirect back to the listing page.
    return $this->redirect('entity.data_stream_notification.collection');
  }

}
