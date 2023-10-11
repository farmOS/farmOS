<?php

namespace Drupal\farm_quick\Plugin\QuickForm;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Interface for quick forms.
 */
interface QuickFormInterface extends FormInterface {

  /**
   * Returns the quick form ID.
   *
   * @return string
   *   The quick form ID.
   */
  public function getQuickId();

  /**
   * Sets the quick form ID.
   *
   * @param string $id
   *   The quick form ID.
   */
  public function setQuickId(string $id);

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
   * Returns the list of access permissions for the quick form.
   *
   * @return string[]
   *   An array of permission strings.
   */
  public function getPermissions();

  /**
   * Checks access for the quick form.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account);

}
