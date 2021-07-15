<?php

namespace Drupal\farm_quick;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Interface for quick forms.
 */
interface QuickFormInterface extends PluginInspectionInterface, ContainerFactoryPluginInterface {

  /**
   * Returns the quick form ID.
   *
   * @return string
   *   The quick form ID.
   */
  public function getId();

  /**
   * Returns the quick form label.
   *
   * @return string
   *   The quick form label.
   */
  public function getLabel();

  /**
   * Returns the quick form description.
   *
   * @return string
   *   The quick form description.
   */
  public function getDescription();

  /**
   * Returns the quick form help text.
   *
   * @return string
   *   The quick form help text.
   */
  public function getHelpText();

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state);

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state);

}
