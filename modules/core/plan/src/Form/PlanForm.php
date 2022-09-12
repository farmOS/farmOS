<?php

namespace Drupal\plan\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for plan entities.
 *
 * @ingroup plan
 */
class PlanForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity_url = $this->entity->toUrl()->setAbsolute()->toString();
    $this->messenger()->addMessage($this->t('Saved plan: <a href=":url">%label</a>', [':url' => $entity_url, '%label' => $this->entity->label()]));
    $form_state->setRedirectUrl($this->entity->toUrl());
    return $status;
  }

}
