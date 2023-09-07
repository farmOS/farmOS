<?php

namespace Drupal\farm_ui_theme\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderCallbackInterface;

/**
 * Entity form for gin content form styling.
 */
class GinContentFormBase extends ContentEntityForm implements RenderCallbackInterface {

  /**
   * {@inheritdoc}
   */
  protected function getNewRevisionDefault() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity_type_label = $this->entity->getEntityType()->getSingularLabel();
    $entity_url = $this->entity->toUrl()->setAbsolute()->toString();
    $this->messenger()->addMessage($this->t('Saved %entity_type_label: <a href=":url">%label</a>', ['%entity_type_label' => $entity_type_label, ':url' => $entity_url, '%label' => $this->entity->label()]));
    $form_state->setRedirectUrl($this->entity->toUrl());
    return $status;
  }

}
