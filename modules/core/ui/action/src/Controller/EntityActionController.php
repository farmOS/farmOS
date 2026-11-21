<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\system\Entity\Action;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Entity action controller.
 */
class EntityActionController extends ControllerBase {

  /**
   * Checks access for a specific entity action plugin.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   * @param \Drupal\system\Entity\Action $action
   *   The action.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, EntityInterface $entity, Action $action) {
    return $action->getPlugin()->access($entity, $account, TRUE);
  }

  /**
   * Execute the action and/or redirect.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   * @param \Drupal\system\Entity\Action $action
   *   The action.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Returns a render array.
   */
  public function execute(EntityInterface $entity, Action $action): RedirectResponse {

    // Execute the action.
    $action->execute([$entity]);

    // Get the entity route name and parameters.
    $entity_type = $entity->getEntityTypeId();
    $entity_route_name = 'entity.' . $entity_type . '.canonical';
    $entity_route_parameters = [$entity_type => $entity->id()];

    // If the action has a confirmation form, redirect to it.
    // Add a destination parameter to redirect back to the entity afterward.
    $definition = $action->getPluginDefinition();
    if (!empty($definition['confirm_form_route_name'])) {
      return $this->redirect($definition['confirm_form_route_name'], [], ['query' => ['destination' => $entity->toUrl()->toString()]]);
    }

    // Set a message for the user.
    $this->messenger()->addStatus($this->t('Performed action on %entity_label: @action_label', ['%entity_label' => $entity->label(), '@action_label' => $action->label()]));

    // Redirect back to the entity.
    return $this->redirect($entity_route_name, $entity_route_parameters);
  }

}
