<?php

namespace Drupal\farm_update\EventSubscriber;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Update\UpdateHookRegistry;
use Drupal\Core\Update\UpdateRegistry;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event subscriber for notifying users of outstanding database updates.
 */
class DatabaseUpdateSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The update registry service.
   *
   * @var \Drupal\Core\Update\UpdateHookRegistry
   */
  protected $updateRegistry;

  /**
   * The post update registry.
   *
   * @var \Drupal\Core\Update\UpdateRegistry
   */
  protected $postUpdateRegistry;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * DatabaseUpdateSubscriber constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Update\UpdateHookRegistry $update_registry
   *   The update registry service.
   * @param \Drupal\Core\Update\UpdateRegistry $post_update_registry
   *   The post update registry.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(ModuleHandlerInterface $module_handler, UpdateHookRegistry $update_registry, UpdateRegistry $post_update_registry, MessengerInterface $messenger) {
    $this->moduleHandler = $module_handler;
    $this->updateRegistry = $update_registry;
    $this->postUpdateRegistry = $post_update_registry;
    $this->messenger = $messenger;
  }

  /**
   * Get subscribed events.
   *
   * @inheritdoc
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['databaseUpdateCheck'];
    return $events;
  }

  /**
   * Check for outstanding database updates.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The RequestEvent object.
   */
  public function databaseUpdateCheck(RequestEvent $event) {

    // Check installed modules for pending updates.
    // This is adapted directly from the core system_requirements().
    $has_pending_updates = FALSE;
    foreach ($this->moduleHandler->getModuleList() as $module => $filename) {
      $updates = $this->updateRegistry->getAvailableUpdates($module);
      if ($updates) {
        $default = $this->updateRegistry->getInstalledVersion($module);
        if (max($updates) > $default) {
          $has_pending_updates = TRUE;
          break;
        }
      }
    }
    if (!$has_pending_updates) {
      $missing_post_update_functions = $this->postUpdateRegistry->getPendingUpdateFunctions();
      if (!empty($missing_post_update_functions)) {
        $has_pending_updates = TRUE;
      }
    }

    // If there are pending updates, display a message.
    if ($has_pending_updates) {
      $message = $this->t('Some modules have database schema updates to install. You should run the <a href=":update">database update script</a> immediately.', [':update' => Url::fromRoute('system.db_update')->toString()]);
      $this->messenger->addWarning($message);
    }
  }

}
