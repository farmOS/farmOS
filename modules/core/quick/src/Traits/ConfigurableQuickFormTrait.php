<?php

namespace Drupal\farm_quick\Traits;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Implements \Drupal\Component\Plugin\ConfigurableQuickFormInterface.
 *
 * @ingroup farm
 */
trait ConfigurableQuickFormTrait {

  use ConfigurableTrait;
  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * Returns the quick form ID.
   *
   * This must be implemented by the quick form class that uses this trait.
   *
   * @see \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface
   *
   * @return string
   *   The quick form ID.
   */
  abstract public function getQuickId();

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {

    // @todo Save configuration entity.

    // Add a status message.
    $this->messenger->addStatus($this->t('Configuration saved.'));
  }

}
