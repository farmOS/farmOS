<?php

namespace Drupal\farm_birth\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\log\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Sync child asset fields to reflect those saved in a birth log.
 */
class LogEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * MyModuleService constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogEvent::INSERT => 'syncBirthChildren',
      LogEvent::UPDATE => 'syncBirthChildren',
    ];
  }

  /**
   * Sync child asset fields to reflect those saved in a birth log.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function syncBirthChildren(LogEvent $event): void {

    // Get the log entity from the event.
    $log = $event->log;

    // If this is not a birth log, bail.
    if ($log->bundle() != 'birth') {
      return;
    }

    // Load mother asset.
    /** @var \Drupal\asset\Entity\AssetInterface $mother */
    $mothers = $log->get('mother')->referencedEntities();
    $mother = reset($mothers);

    // Load children assets.
    /** @var \Drupal\asset\Entity\AssetInterface[] $children */
    $children = $log->get('asset')->referencedEntities();

    // If the log doesn't reference any children, bail.
    if (empty($children)) {
      return;
    }

    // Iterate through the children.
    foreach ($children as $child) {
      $save = FALSE;
      $revision_log = [];

      // If the child is an animal, and their date of birth does not match the
      // timestamp of the birth log, sync it.
      if ($child->bundle() == 'animal' && $child->get('birthdate')->value != $log->get('timestamp')->value) {
        $args = [
          ':child_url' => $child->toUrl()->toString(),
          '%child_name' => $child->label(),
        ];
        $message = $this->t('<a href=":child_url">%child_name</a> date of birth was updated to match their birth log.', $args);
        $this->messenger->addMessage($message);
        $revision_log[] = $message;
        $child->birthdate = $log->get('timestamp')->value;
        $save = TRUE;
      }

      // If a mother is specified, and the child does not have any parents,
      // add the mother to the child's parent reference field.
      if (!empty($mother)) {
        $parents = $child->get('parent')->referencedEntities();
        if (empty($parents)) {
          $args = [
            ':mother_url' => $mother->toUrl()->toString(),
            '%mother_name' => $mother->label(),
            ':child_url' => $child->toUrl()->toString(),
            '%child_name' => $child->label(),
          ];
          $message = $this->t('<a href=":mother_url">%mother_name</a> added as a parent of <a href=":child_url">%child_name</a>.', $args);
          $this->messenger->addMessage($message);
          $revision_log[] = $message;
          $child->parent[] = ['target_id' => $mother->id()];
          $save = TRUE;
        }
      }

      // Save the child, if necessary.
      if ($save) {
        $revision_log[] = $this->t('Birth log saved: <a href=":birth_url">%birth_label</a>', [':birth_url' => $log->toUrl()->toString(), '%birth_label' => $log->label()]);
        $child->setRevisionLogMessage(implode(" ", $revision_log));
        $child->save();
      }
    }
  }

}
