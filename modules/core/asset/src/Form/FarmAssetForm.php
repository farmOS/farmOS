<?php

namespace Drupal\farm_asset\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for farm_asset entities.
 *
 * @ingroup farm_asset
 */
class FarmAssetForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $this->messenger()->addMessage($this->t('Saved the %label farm asset.', ['%label' => $this->entity->label()]));
    $account = $this->currentUser();
    if ($account->hasPermission('access farm_asset overview')) {
      $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    }
    else {
      $form_state->setRedirectUrl($this->entity->toUrl());
    }
  }

}
