<?php

namespace Drupal\asset\Plugin\Action;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Base class for actions that change the asset status state.
 */
abstract class AssetStateChangeBase extends EntityActionBase {

  /**
   * The target state to transition to.
   *
   * @var string
   */
  protected $targetState;

  /**
   * {@inheritdoc}
   */
  public function execute(AssetInterface $asset = NULL) {

    // Bail if there is no asset.
    if (empty($asset)) {
      return;
    }

    // Apply the transition to target state if not already the current state.
    /** @var \Drupal\state_machine\Plugin\Field\FieldType\StateItemInterface $state_item */
    $state_item = $asset->get('status')->first();
    if ($state_item->getOriginalId() !== $this->targetState && $transition = $state_item->getWorkflow()->findTransition($state_item->getOriginalId(), $this->targetState)) {
      $state_item->applyTransition($transition);
      $asset->setNewRevision(TRUE);

      // Validate the entity before saving.
      $violations = $asset->validate();
      if ($violations->count() > 0) {
        $this->messenger()->addWarning(
          $this->t('Could not change the status of <a href=":entity_link">%entity_label</a>: validation failed.',
            [
              ':entity_link' => $asset->toUrl()->setAbsolute()->toString(),
              '%entity_label' => $asset->label(),
            ],
          ),
        );
        return;
      }

      $asset->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\asset\Entity\AssetInterface $object */
    // First check entity and state field access.
    $result = $object->get('status')->access('edit', $account, TRUE)
      ->andIf($object->access('update', $account, TRUE));

    // Save the state field.
    /** @var \Drupal\state_machine\Plugin\Field\FieldType\StateItemInterface $state_item */
    $state_item = $object->get('status')->first();

    // If the state field is already in the target state, return early.
    // The workflow will not allow a transition to the same state but the
    // action itself does not need to fail.
    if ($state_item->getOriginalId() === $this->targetState) {
      return $return_as_object ? $result : $result->isAllowed();
    }

    // Check that the target state exists for the workflow.
    $workflow = $state_item->getWorkflow();
    $target_state = $workflow->getState($this->targetState);

    // Deny access if the workflow does not support the target state.
    if (empty($target_state)) {
      $result = $result->orIf(AccessResult::forbidden(
        $this->t('The %workflow workflow does not support the %target_state state.', ['%workflow' => $workflow->getLabel(), '%target_state' => $this->targetState]),
      ));
    }
    // Else check that a transition exists to the desired target state.
    else {
      $transition = $workflow->findTransition($state_item->getOriginalId(), $this->targetState);
      $result = $result->orIf(AccessResult::forbiddenIf(
        empty($transition) || !$state_item->isTransitionAllowed($transition->getId()),
        $this->t('The state transition from %original_state to %target_state is not allowed.', ['%original' => $state_item->getOriginalLabel(), '%target_state' => $target_state->getLabel()]),
      ));
    }

    return $return_as_object ? $result : $result->isAllowed();
  }

}
