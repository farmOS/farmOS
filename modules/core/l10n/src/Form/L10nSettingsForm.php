<?php

namespace Drupal\farm_l10n\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Form\NegotiationSelectedForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure the selected language negotiation method for this site.
 *
 * @phpstan-ignore-next-line
 */
class L10nSettingsForm extends NegotiationSelectedForm {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor for L10nSettingsForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_l10n_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Provide an option to update the default language of existing users.
    $form['update_existing_users'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Update existing users'),
      '#description' => $this->t('Update the language of all existing users to match the default language.'),
      '#default_value' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Initiate a batch operation to update the default language of all users
    // (except user 1).
    if ($form_state->getValue('update_existing_users')) {
      $operations = [];
      $query = $this->entityTypeManager->getStorage('user')->getQuery()->accessCheck(FALSE);
      $uids = $query->condition('uid', '1', '!=')->execute();
      foreach ($uids as $uid) {
        $operations[] = [
          [__CLASS__, 'updateUserLanguage'],
          [$uid, $form_state->getValue('selected_langcode')],
        ];
      }
      batch_set([
        'operations' => $operations,
        'title' => $this->t('Updating user languages'),
        'error_message' => $this->t('The user language update has encountered an error.'),
      ]);
    }
  }

  /**
   * Update the language for a user.
   *
   * @param int $uid
   *   The user ID.
   * @param string $langcode
   *   The new langcode to assign.
   */
  public static function updateUserLanguage(int $uid, string $langcode) {
    /** @var \Drupal\user\UserInterface $user */
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
    $user->set('preferred_langcode', $langcode);
    $user->set('preferred_admin_langcode', $langcode);
    $user->save();
  }

}
