<?php

namespace Drupal\Tests\farm_l10n\Kernel;

use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Tests\farm_api\Kernel\FarmApiTest;

/**
 * Tests farmOS API features.
 *
 * @group farm
 */
class FarmLocalizationApiTest extends FarmApiTest {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'config_translation',
    'farm_l10n',
    'language',
    'locale',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['language']);
    $this->installSchema('locale', ['locales_source', 'locales_target', 'locales_location']);

    // Configure the enabled language detection methods.
    // This would normally be done by farm_l10n_install(), which does not run
    // in Kernel tests.
    \Drupal::configFactory()->getEditable('language.types')->set('negotiation.language_interface.enabled', [
      'language-user' => 0,
      'language-selected' => 50,
    ])->save();

    // Create a language for testing (Spanish).
    ConfigurableLanguage::createFromLangcode('es')->save();

    // In order to reflect the changes for a multilingual site in the container
    // we have to rebuild it.
    \Drupal::service('kernel')->rebuildContainer();

    // Make Spanish the "selected language" for the site.
    \Drupal::configFactory()->getEditable('language.negotiation')->set('selected_langcode', 'es')->save();

    // Set up a user with the farm_manager role and the non-default language.
    $user = $this->setUpCurrentUser([
      'preferred_langcode' => 'es',
      'preferred_admin_langcode' => 'es',
    ], [], FALSE);
    $user->addRole('farm_manager');
  }

}
