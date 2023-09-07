<?php

namespace Drupal\farm_ui_theme\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderCallbackInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Entity form for gin content form styling.
 */
class GinContentFormBase extends ContentEntityForm implements RenderCallbackInterface {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a ContentEntityForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info, TimeInterface $time, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('date.formatter'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getNewRevisionDefault() {
    return TRUE;
  }

  /**
   * Function that returns an array of tab definitions to add.
   *
   * @return array
   *   Array of tab definitions keyed by tab id. Each definition should
   *   provide a location, title and weight.
   */
  protected function getFieldGroups() {
    return [
      'default' => [
        'location' => 'main',
        'title' => 'Default',
        'weight' => -50,
      ],
      'meta' => [
        'location' => 'sidebar',
        'title' => $this->t('Meta'),
        'weight' => 0,
      ],
      'location' => [
        'location' => 'main',
        'title' => $this->t('Location'),
        'weight' => 50,
      ],
      'file' => [
        'location' => 'main',
        'title' => $this->t('Files'),
        'weight' => 150,
      ],
      'revision' => [
        'location' => 'sidebar',
        'title' => $this->t('Revision information'),
        'weight' => 200,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Add process callback to alter form after GinContentFormHelper.
    $form['#process'][] = '::processContentForm';

    // Only alter the form display if farm_ui_theme.use_field_group is TRUE
    // or if the form display is new and not saved.
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $form_display = $form_state->get('form_display');
    if (!$form_display || !$form_display->getThirdPartySetting('farm_ui_theme', 'use_field_group', $form_display->isNew())) {
      return $form;
    }

    // Add field groups.
    $field_groups = $this->getFieldGroups();
    if (!empty($field_groups)) {

      // Disable HTML5 validation on the form element since it does not work
      // with vertical tabs.
      $form['#attributes']['novalidate'] = 'novalidate';

      // Vary field group titles based on entity and bundle.
      if (isset($field_groups['default'])) {
        $field_groups['default']['title'] = $this->entity->type->entity->label();
      }

      // Create parent for all tabs.
      $form['tabs'] = [
        '#type' => 'vertical_tabs',
        '#default_tab' => 'edit-setup',
      ];

      // Create tabs.
      foreach ($field_groups as $tab_id => $tab_info) {
        $tab_id = "{$tab_id}_field_group";
        $tab_group = $tab_info['location'] == 'sidebar' ? 'advanced' : 'tabs';
        $form[$tab_id] = [
          '#type' => 'details',
          '#title' => $tab_info['title'],
          '#group' => $tab_group,
          '#optional' => TRUE,
          '#weight' => $tab_info['weight'],
          '#open' => TRUE,
        ];
      }

      // Set field group for each display component.
      foreach ($form_display->getComponents() as $field_id => $options) {
        if (isset($form[$field_id]) && $render = $form_display->getRenderer($field_id)) {
          $tab_id = $render->getThirdPartySetting('farm_ui_theme', 'field_group', 'default');
          $form[$field_id]['#group'] = "{$tab_id}_field_group";
        }
      }
    }

    // Add authoring information for existing entities.
    if (!$this->entity->isNew()) {
      $changed = $this->dateFormatter->format($this->entity->getChangedTime(), 'short', '', $this->currentUser()->getTimeZone(), '');
      $created = $this->dateFormatter->format($this->entity->getCreatedTime(), 'short', '', $this->currentUser()->getTimeZone(), '');
      $form['revision_field_group']['revision_meta'] = [
        '#theme' => 'item_list',
        '#items' => [
          $this->t('Author: @author', ['@author' => $this->entity->getOwner()->getAccountName()]),
          $this->t('Last saved: @timestamp', ['@timestamp' => $changed]),
          $this->t('Created: @timestamp', ['@timestamp' => $created]),
        ],
      ];
    }

    return $form;
  }

  /**
   * Process function to update form after GinContentFormHelper.
   *
   * @param array $form
   *   The form array to alter.
   *
   * @return array
   *   The form array.
   *
   * @see \Drupal\gin\GinContentFormHelper
   */
  public function processContentForm(array $form): array {

    // Disable the default meta group provided by Gin.
    unset($form['meta']);

    // Bail if the status field is not included.
    if (!isset($form['status'])) {
      return $form;
    }

    // Assign correct status group after GinContentFormHelper.
    if (isset($form['meta_field_group'])) {
      $form['status']['#group'] = 'meta_field_group';
    }
    // Else unset the status group that is set by GinContentFormHelper.
    else {
      unset($form['status']['#group']);
    }

    return $form;
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
