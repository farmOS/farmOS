<?php

declare(strict_types=1);

namespace Drupal\farm_ui_theme\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderCallbackInterface;
use Drupal\farm_form\Traits\FarmFormProtectionTrait;
use Drupal\user\EntityOwnerInterface;

/**
 * Entity form for gin content form styling.
 */
class GinContentFormBase extends ContentEntityForm implements RenderCallbackInterface {

  use AutowireTrait;
  use FarmFormProtectionTrait;

  public function __construct(
    EntityRepositoryInterface $entity_repository,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    TimeInterface $time,
    protected DateFormatterInterface $dateFormatter,
    ModuleHandlerInterface $module_handler,
    ConfigFactoryInterface $config_factory,
  ) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->setModuleHandler($module_handler);
    $this->setConfigFactory($config_factory);
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
    $entity_type = $this->entity->getEntityTypeId();
    $bundle = $this->entity->bundle();
    $field_groups = [
      'default' => [
        'location' => 'main',
        'title' => $this->t('General'),
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
        'weight' => 500,
      ],
    ] + $this->moduleHandler->invokeAll('farm_ui_theme_field_groups', [$entity_type, $bundle]);
    return $field_groups;
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
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface|null $form_display */
    $form_display = $form_state->get('form_display');
    if (!$form_display || !$form_display->getThirdPartySetting('farm_ui_theme', 'use_field_group', $form_display->isNew())) {
      return $form;
    }

    // Attach content form styles.
    $form['#attached']['library'][] = 'farm_ui_theme/content_form';

    // Add field groups, and allow modules to alter them.
    $field_groups = $this->getFieldGroups();
    if (!empty($field_groups)) {

      // Disable HTML5 validation on the form element since it does not work
      // with vertical tabs.
      $form['#attributes']['novalidate'] = 'novalidate';

      // Guard malformed numeric input that browsers would submit as empty.
      $form['#attributes']['class'][] = 'farm-number-validation';
      $form['#attached']['library'][] = 'farm_ui_theme/number_validation';

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
          '#open' => $tab_id === 'default_field_group' || $tab_id === 'meta_field_group',
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

    // Comments are a special case because the CommentWidget sets the #group
    // for the widget to advanced but this weight is never set.
    if (isset($form['comment']['widget'][0])) {
      $form['comment']['widget'][0]['#weight'] = $form['comment']['#weight'];
    }

    // Add authoring information for existing entities.
    if (!$this->entity->isNew()) {
      $form['revision_field_group']['#optional'] = FALSE;
      $revision_items = [];

      // Only add created metadata if available.
      if (
        $this->entity instanceof EntityOwnerInterface
        && method_exists($this->entity, 'getCreatedTime')
      ) {
        $author = $this->entity->getOwner()->getAccountName();
        $date = $this->dateFormatter->format($this->entity->getCreatedTime(), 'short', '', $this->currentUser()->getTimeZone(), '');
        $revision_items[] = $this->t('Created @timestamp by @author', ['@timestamp' => $date, '@author' => $author]);
      }

      // Only add revision information if available.
      if ($this->entity instanceof RevisionLogInterface && $user = $this->entity->getRevisionUser()) {
        $changed = $this->dateFormatter->format($this->entity->getRevisionCreationTime(), 'short', '', $this->currentUser()->getTimeZone(), '');
        $revision_items[] = $this->t('Last saved: @timestamp by @author', ['@timestamp' => $changed, '@author' => $user->label()]);
      }
      // Else only add changed metadata if available.
      elseif ($this->entity instanceof EntityChangedInterface) {
        $changed = $this->dateFormatter->format($this->entity->getChangedTime(), 'short', '', $this->currentUser()->getTimeZone(), '');
        $revision_items[] = $this->t('Last saved: @timestamp', ['@timestamp' => $changed]);
      }

      // Add to revision field group.
      $form['revision_field_group']['revision_meta'] = [
        '#theme' => 'item_list',
        '#items' => $revision_items,
      ];

      // Enable form protection.
      $this->enableFormProtection($form);
    }

    return $form;
  }

  /**
   * Process function to update form after GinContentFormHelper.
   *
   * @param array $form
   *   The form array to alter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The form array.
   *
   * @see \Drupal\gin\GinContentFormHelper
   */
  public function processContentForm(array $form, FormStateInterface $form_state): array {

    // Increase weight of Gin's meta group to bottom of the sidebar.
    // This group has special styling and includes additional action
    // buttons like Delete that should be included on the page.
    $form['meta']['#weight'] = 500;

    // Assign correct status group after GinContentFormHelper.
    if (isset($form['status']) && isset($form['meta_field_group'])) {
      $form['status']['#group'] = 'meta_field_group';
    }
    // Else unset the status group that is set by GinContentFormHelper.
    else {
      unset($form['status']['#group']);
    }

    // Remove the sidebar if the display is not using field groups.
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface|null $form_display */
    $form_display = $form_state->get('form_display');
    if (!$form_display || !$form_display->getThirdPartySetting('farm_ui_theme', 'use_field_group', $form_display->isNew())) {

      // Revert Gin changes to advanced and revision information.
      $form['advanced']['#type'] = 'vertical_tabs';
      $form['revision_information']['#type'] = 'details';
      $form['revision_information']['#group'] = 'advanced';

      // Do not use the node_edit_form theme.
      // The template includes elements for the sidebar.
      unset($form['#theme']);
      unset($form['gin_actions']);
      unset($form['gin_sidebar']);

      // Remove gin sidebar and edit_form libraries.
      if (($index = array_search('gin/sidebar', $form['#attached']['library'])) !== FALSE) {
        unset($form['#attached']['library'][$index]);
      }
      if (($index = array_search('gin/edit_form', $form['#attached']['library'])) !== FALSE) {
        unset($form['#attached']['library'][$index]);
      }
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
