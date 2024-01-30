<?php

namespace Drupal\farm_quick\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\farm_quick\Entity\QuickFormInstance;
use Drupal\farm_quick\QuickFormPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Form that renders quick form configuration forms.
 */
class QuickFormEntityForm extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface
   */
  protected $entity;

  /**
   * The quick form plugin manager.
   *
   * @var \Drupal\farm_quick\QuickFormPluginManager
   */
  protected $quickFormPluginManager;

  /**
   * Constructs a new QuickFormEntityForm object.
   *
   * @param \Drupal\farm_quick\QuickFormPluginManager $quick_form_plugin_manager
   *   The quick form plugin manager.
   */
  public function __construct(QuickFormPluginManager $quick_form_plugin_manager) {
    $this->quickFormPluginManager = $quick_form_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.quick_form'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state, string $plugin = NULL) {
    $form = parent::form($form, $form_state);

    // Add tabs if the quick form plugin is configurable.
    $tab_group = NULL;
    if ($this->entity->getPlugin()->isConfigurable()) {
      $form['tabs'] = [
        '#type' => 'vertical_tabs',
      ];
      $form['quick_form'] = [
        '#type' => 'details',
        '#title' => $this->t('Quick form'),
        '#group' => 'tabs',
      ];
      $tab_group = 'quick_form';

      // Render the plugin form in settings tab.
      $form['settings_tab'] = [
        '#type' => 'details',
        '#title' => Html::escape($this->entity->getPlugin()->getLabel()),
        '#group' => 'tabs',
        '#weight' => 50,
      ];
      $form['settings'] = [
        '#tree' => TRUE,
        '#type' => 'container',
        '#group' => 'settings_tab',
      ];
      $form['settings'] = $this->entity->getPlugin()->buildConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#required' => TRUE,
      '#group' => $tab_group,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#machine_name' => [
        'exists' => '\Drupal\farm_quick\Entity\QuickFormInstance::load',
      ],
      '#disabled' => !$this->entity->isNew() || $this->getRequest()->get('override'),
      '#group' => $tab_group,
    ];

    // Provide default label and ID for existing config entities
    // or if the override parameter is set.
    if (!$this->entity->isNew() || $this->getRequest()->get('override')) {
      $form['label']['#default_value'] = $this->entity->label();
      $form['id']['#default_value'] = $this->entity->id();
    }

    // Adjust form title.
    if ($this->entity->isNew()) {
      $form['#title'] = $this->t('Add quick form: @label', ['@label' => $this->entity->getPlugin()->getLabel()]);
      if ($this->getRequest()->get('override')) {
        $form['#title'] = $this->t('Override quick form: @label', ['@label' => $this->entity->getPlugin()->getLabel()]);
      }
    }
    else {
      $form['#title'] = $this->t('Edit quick form: @label', ['@label' => $this->entity->label()]);
    }

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#description' => $this->t('A brief description of this quick form.'),
      '#default_value' => $this->entity->getDescription(),
      '#group' => $tab_group,
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#description' => $this->t('Enable the quick form.'),
      '#default_value' => $this->entity->status(),
      '#group' => $tab_group,
    ];

    $form['helpText'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Help Text'),
      '#description' => $this->t('Help text to display for the quick form.'),
      '#default_value' => $this->entity->getHelpText(),
      '#group' => $tab_group,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Validate plugin form.
    if ($this->entity->getPlugin()->isConfigurable()) {
      $this->entity->getPlugin()->validateConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Submit plugin form.
    if ($this->entity->getPlugin()->isConfigurable()) {
      $this->entity->getPlugin()->submitConfigurationForm($form['settings'], SubformState::createForSubform($form['settings'], $form, $form_state));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity_type_label = $this->entity->getEntityType()->getSingularLabel();
    $this->messenger()->addMessage($this->t('Saved @entity_type_label: %label', ['@entity_type_label' => $entity_type_label, '%label' => $this->entity->label()]));
    $form_state->setRedirect('entity.quick_form.collection');
    return $status;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityFromRouteMatch(RouteMatchInterface $route_match, $entity_type_id) {

    // Get existing quick form entity from route parameter.
    if ($route_match->getRawParameter($entity_type_id) !== NULL) {
      $entity = $route_match->getParameter($entity_type_id);
    }
    // Else create a new quick form entity, the plugin must be specified.
    else {
      if (($plugin = $route_match->getRawParameter('plugin')) && $this->quickFormPluginManager->hasDefinition($plugin)) {
        $entity = QuickFormInstance::create(['plugin' => $plugin]);
        if ($this->getRequest()->get('override')) {
          $entity->set('id', $plugin);
        }
      }
    }

    if (empty($entity)) {
      throw new NotFoundHttpException();
    }

    return $entity;
  }

}
