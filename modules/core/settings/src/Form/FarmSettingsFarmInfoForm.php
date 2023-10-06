<?php

namespace Drupal\farm_settings\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\TimeZoneFormHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for configuring basic farm info.
 *
 * @ingroup farm
 */
class FarmSettingsFarmInfoForm extends ConfigFormBase {

  /**
   * The country manager.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected $countryManager;

  /**
   * Constructs a RegionalForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Locale\CountryManagerInterface $country_manager
   *   The country manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CountryManagerInterface $country_manager) {
    parent::__construct($config_factory);
    $this->countryManager = $country_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('country_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_settings_farm_info';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'system.date',
      'system.site',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Set the form title.
    $form['#title'] = $this->t('Configure Farm Info');

    // Get the system.site config.
    $site = $this->config('system.site');

    // Textfield to edit site name.
    $form['site_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Farm name'),
      '#default_value' => $site->get('name'),
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    // Get the system.date config.
    $system_date = $this->config('system.date');

    // Get countries.
    $countries = $this->countryManager->getList();

    // Default country select.
    $form['default_country'] = [
      '#type' => 'select',
      '#title' => $this->t('Default country'),
      '#empty_value' => '',
      '#default_value' => $system_date->get('country.default'),
      '#options' => $countries,
      '#attributes' => ['class' => ['country-detect']],
    ];

    // Get list of timezones.
    $timezones = TimeZoneFormHelper::getOptionsList();

    // Dropdown to select default timezone.
    $form['default_timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Default timezone'),
      '#description' => $this->t('The default timezone of the farmOS server. Note that users can configure individual timezones later.'),
      '#options' => $timezones,
      '#default_value' => $system_date->get('timezone.default'),
      '#required' => TRUE,
    ];

    // Submit button.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the submitted site name.
    $site_name = $form_state->getvalue('site_name');

    // Update system.site config.
    $this->configFactory->getEditable('system.site')
      ->set('name', $site_name)
      ->save();

    // Get the submitted country.
    $default_country = $form_state->getValue('default_country');

    // Get the submitted timezone.
    $default_timezone = $form_state->getValue('default_timezone');

    // Update system.date config.
    $this->configFactory->getEditable('system.date')
      ->set('timezone.default', $default_timezone)
      ->set('country.default', $default_country)
      ->save();

    // Display message from parent submitForm.
    parent::submitForm($form, $form_state);
  }

}
