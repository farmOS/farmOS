<?php

namespace Drupal\data_stream_notification\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\data_stream_notification\NotificationConditionManagerInterface;
use Drupal\data_stream_notification\NotificationDeliveryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Data stream notification entity form.
 */
class DataStreamNotificationForm extends EntityForm {

  /**
   * The notification condition manager service.
   *
   * @var \Drupal\data_stream_notification\NotificationConditionManagerInterface
   */
  protected $conditionManager;

  /**
   * The notification delivery manager service.
   *
   * @var \Drupal\data_stream_notification\NotificationDeliveryManagerInterface
   */
  protected $deliveryManager;

  /**
   * Constructs a new DataStreamNotificationForm object.
   *
   * @param \Drupal\data_stream_notification\NotificationConditionManagerInterface $condition_manager
   *   The notification condition manager service.
   * @param \Drupal\data_stream_notification\NotificationDeliveryManagerInterface $delivery_manager
   *   The notification delivery manager service.
   */
  public function __construct(NotificationConditionManagerInterface $condition_manager, NotificationDeliveryManagerInterface $delivery_manager) {
    $this->conditionManager = $condition_manager;
    $this->deliveryManager = $delivery_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.data_stream_notification_condition'),
      $container->get('plugin.manager.data_stream_notification_delivery'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function init(FormStateInterface $form_state) {
    parent::init($form_state);

    // Init the condition and delivery plugins in form_state.
    $notification = $this->entity;
    foreach (['condition', 'delivery'] as $plugin_type) {
      $form_state->setValue($plugin_type, $notification->get($plugin_type));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $notification = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $notification->label(),
      '#description' => $this->t('Label for the data stream notification.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $notification->id(),
      '#machine_name' => [
        'exists' => '\Drupal\data_stream_notification\Entity\DataStreamNotification::load',
      ],
      '#disabled' => !$notification->isNew(),
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $notification->status(),
    ];

    // @todo Improve data stream entity selection.
    // @todo Support multiple data streams.
    $default = $notification->get('data_stream') ?? 0;
    $form['data_stream'] = [
      '#type' => 'number',
      '#title' => $this->t('Data stream ID'),
      '#required' => TRUE,
      '#default_value' => $default,
    ];

    $form['activation_threshold'] = [
      '#type' => 'number',
      '#title' => $this->t('Activation threshold'),
      '#description' => $this->t('How many consecutive times the conditions must be met before notifications are delivered. Defaults to 1, sending a notification the first time conditions are met.'),
      '#default_value' => $notification->get('activation_threshold') ?? 1,
      '#min' => 1,
    ];

    $form['deactivation_threshold'] = [
      '#type' => 'number',
      '#title' => $this->t('Deactivation threshold'),
      '#description' => $this->t('How many consecutive times the conditions must fail before the notification becomes inactive and will require the activation threshold again. Defaults to 1, making the notification inactive the first time conditions are not met.'),
      '#default_value' => $notification->get('deactivation_threshold') ?? 1,
      '#min' => 1,
    ];

    // Define some info about the plugin types.
    $plugin_types = [
      'condition' => [
        'label' => $this->t('Condition'),
      ],
      'delivery' => [
        'label' => $this->t('Delivery'),
      ],
    ];
    foreach ($plugin_types as $plugin_type => $plugin_info) {

      // Get the plugin type manager service.
      $manager_name = $plugin_type . 'Manager';
      $manager = $this->$manager_name;

      // Create a wrapper for the plugin type.
      $wrapper = $plugin_type . '_wrapper';
      $form[$wrapper] = [
        '#type' => 'details',
        '#title' => $plugin_info['label'],
        '#open' => TRUE,
        '#prefix' => "<div id=\"$plugin_type-fieldset-wrapper\">",
        '#suffix' => '</div>',
      ];

      // Add an actions element.
      $form[$wrapper][$plugin_type . '_actions'] = [
        '#type' => 'actions',
      ];

      // Get the available options for the plugin type.
      $plugin_options = array_map(function ($definition) {
        return $definition['label'];
      }, $manager->getDefinitions());
      $default = array_keys($plugin_options)[0];

      // Select field for the type of plugin to add.
      $form[$wrapper][$plugin_type . '_actions'][$plugin_type . '_type'] = [
        '#type' => 'select',
        '#title' => $this->t('@type type', ['@type' => $plugin_info['label']]),
        '#title_display' => 'attribute',
        '#options' => $plugin_options,
        '#default_value' => $default,
      ];

      // Button to add another plugin.
      $form[$wrapper][$plugin_type . '_actions'][$plugin_type . '_add'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add @type', ['@type' => $plugin_info['label']]),
        '#submit' => ['::addOne'],
        '#name' => "add-$plugin_type",
        '#ajax' => [
          'callback' => '::updatePlugins',
          'wrapper' => "$plugin_type-fieldset-wrapper",
        ],
      ];

      // Start a tree to hold an array of plugin definitions.
      $form[$wrapper][$plugin_type] = [
        '#tree' => TRUE,
      ];

      // Add each plugin definition to the form.
      $plugins = $form_state->getValue($plugin_type) ?? [];
      foreach ($plugins as $delta => $plugin_config) {

        // Wrap each plugin definition in a details element.
        $form[$wrapper][$plugin_type][$delta] = [
          '#type' => 'details',
          '#title' => $plugin_options[$plugin_config['type']],
          '#open' => TRUE,
        ];

        // Display the plugin type. This can't be changed.
        $form[$wrapper][$plugin_type][$delta]['type'] = [
          '#type' => 'hidden',
          '#title' => $this->t('Condition type'),
          '#default_value' => $plugin_config['type'],
          '#disabled' => TRUE,
        ];

        // Allow the plugin type to provide a subform.
        $plugin_instance = $manager->createInstance($plugin_config['type'], $plugin_config);
        $subform_state = SubformState::createForSubform($form[$wrapper][$plugin_type][$delta], $form, $form_state);
        $form[$wrapper][$plugin_type][$delta] = $plugin_instance->buildConfigurationForm($form[$wrapper][$plugin_type][$delta], $subform_state);

        // Include the summary in condition plugin titles.
        if ($plugin_type === 'condition' && $summary = $plugin_instance->summary()) {
          $title = $plugin_options[$plugin_config['type']] . ': ' . $summary;
          $form[$wrapper][$plugin_type][$delta]['#title'] = $title;
        }

        // Add button to remove the plugin definition.
        $form[$wrapper][$plugin_type][$delta]['remove'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#submit' => ['::removeOne'],
          '#name' => "remove-$plugin_type-$delta",
          '#ajax' => [
            'callback' => '::updatePlugins',
            'wrapper' => "$plugin_type-fieldset-wrapper",
          ],
          '#attributes' => [
            'class' => ['button--danger'],
          ],
          '#weight' => 100,
        ];
      }
    }

    // Condition operator field.
    $form['condition_wrapper']['condition_operator'] = [
      '#type' => 'select',
      '#title' => $this->t('Operator'),
      '#description' => $this->t('Specify the operator to use when testing conditions. "Or" will trigger the notification when any condition is met; "And" will trigger the notification when all conditions are met.'),
      '#options' => [
        'or' => $this->t('Or'),
        'and' => $this->t('And'),
      ],
      '#default_value' => $notification->get('condition_operator') ?? 'or',
      '#tree' => FALSE,
      '#weight' => -100,
    ];

    $form['delivery_wrapper']['delivery_interval'] = [
      '#type' => 'number',
      '#title' => $this->t('Delivery interval'),
      '#description' => $this->t('Once the notification is active, send notifications every N times conditions are met. Defaults to 1, sending a notification every time conditions are met. Set to 10 to send a notification for every 10th incident. Set to 0 to only send a notification when it first becomes active.'),
      '#default_value' => $notification->get('delivery_interval') ?? 1,
      '#tree' => FALSE,
      '#weight' => -100,
      '#min' => 0,
    ];

    // Allow the existing notification state to be reset on save.
    if (!$notification->isNew()) {
      $current_state = $notification->isActive() ? $this->t('Active') : $this->t('Not active');
      $title = $this->t('Reset the notification state on save. <em>Current state: @state</em>', ['@state' => $current_state]);
      $form['reset_state'] = [
        '#type' => 'checkbox',
        '#title' => $title,
        '#default_value' => TRUE,
        '#weight' => 100,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Check if we are submitting the form.
    $validate_empty_plugins = FALSE;
    $parents = $form_state->getTriggeringElement()['#array_parents'];
    if (count($parents) === 2 && $parents[0] === 'actions' && $parents[1] === 'submit') {
      $validate_empty_plugins = TRUE;
    }

    // Allow each condition subform to validate the form.
    foreach (['condition', 'delivery'] as $plugin_type) {
      $plugins = $form_state->getValue($plugin_type) ?? [];

      // Validate that at least one plugin is provided when submitting the form.
      if ($validate_empty_plugins && empty($plugins)) {
        $form_state->setError($form[$plugin_type . '_wrapper'][$plugin_type . '_actions'][$plugin_type . '_add'], $this->t('At least one @type is required.', ['@type' => $plugin_type]));
      }

      // Validate each plugin.
      foreach ($plugins as $delta => $plugin_config) {

        // Get the plugin type manager service.
        $manager_name = $plugin_type . 'Manager';
        $manager = $this->$manager_name;

        // Allow the plugin type to validate the subform.
        $plugin_instance = $manager->createInstance($plugin_config['type'], $plugin_config);
        $wrapper = $plugin_type . '_wrapper';
        $subform_state = SubformState::createForSubform($form[$wrapper][$plugin_type][$delta], $form, $form_state);
        $plugin_instance->validateConfigurationForm($form[$wrapper][$plugin_type][$delta], $subform_state);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Allow each plugin subform to submit the form.
    foreach (['condition', 'delivery'] as $plugin_type) {
      $plugins = $form_state->getValue($plugin_type) ?? [];
      foreach ($plugins as $delta => $plugin_config) {

        // Get the plugin type manager service.
        $manager_name = $plugin_type . 'Manager';
        $manager = $this->$manager_name;

        // Allow the plugin type to provide a subform.
        $plugin_instance = $manager->createInstance($plugin_config['type'], $plugin_config);
        $wrapper = $plugin_type . '_wrapper';
        $subform_state = SubformState::createForSubform($form[$wrapper][$plugin_type][$delta], $form, $form_state);
        $plugin_instance->submitConfigurationForm($form[$wrapper][$plugin_type][$delta], $subform_state);
      }
    }

    // If specified, reset the notification state.
    if ($form_state->getValue('reset_state', FALSE)) {
      $this->entity->resetState();
    }

    // Call the parent method after subforms are submitted.
    parent::submitForm($form, $form_state);
  }

  /**
   * Submit handler for the "Add {plugin_type}" button.
   *
   * Adds additional plugin definitions of the specified type.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {

    // Determine the plugin type from the wrapper id, eg: condition_wrapper.
    $wrapper_id = $form_state->getTriggeringElement()['#array_parents'][0];
    $plugin_type = substr($wrapper_id, 0, -8);

    // Get existing plugins.
    $plugins = $form_state->getValue($plugin_type);

    // Add a new plugin of the configured type.
    $new_plugin_type = $form_state->getValue($plugin_type . '_type');
    $plugins[] = ['type' => $new_plugin_type, 'settings' => []];

    // Update the form.
    $form_state->setValue($plugin_type, $plugins);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "Remove" button.
   *
   * Removes individual plugin definitions.
   */
  public function removeOne(array &$form, FormStateInterface $form_state) {

    // Determine which definition to remove.
    $parents = $form_state->getTriggeringElement()['#array_parents'];
    $plugin_type = $parents[1];
    $delta = $parents[2];

    // Remove the delta from the existing plugins.
    $plugins = $form_state->getValue($plugin_type);
    unset($plugins[$delta]);

    // Update the form.
    $form_state->setValue($plugin_type, $plugins);
    $form_state->setRebuild();
  }

  /**
   * Callback for both the "Add" and "Remove" buttons.
   *
   * Updates all conditions of the given type.
   */
  public function updatePlugins(array &$form, FormStateInterface $form_state) {
    $wrapper_id = $form_state->getTriggeringElement()['#array_parents'][0];
    return $form[$wrapper_id];
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $notification = $this->entity;
    $status = $notification->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label data stream notification.', [
          '%label' => $notification->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label data stream notification.', [
          '%label' => $notification->label(),
        ]));
    }
    $form_state->setRedirectUrl($notification->toUrl('collection'));

    return $status;
  }

  /**
   * {@inheritdoc}
   */
  protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state) {
    parent::copyFormValuesToEntity($entity, $form, $form_state);

    // Save the condition and delivery values, defaulting to an empty array.
    // The parent method will skip these since they are plugin collections.
    $values = $form_state->getValues();
    foreach (['condition', 'delivery'] as $type) {
      $entity->set($type, $values[$type] ?? []);
    }
  }

}
