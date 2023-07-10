<?php

namespace Drupal\Tests\farm_ui_views\Functional;

use Drupal\asset\Entity\Asset;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farm_ui_views taxonomy views routes.
 *
 * @group farm
 */
class TaxonomyTermTasksTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * Test animal asset.
   *
   * @var \Drupal\asset\Entity\Asset
   */
  protected $favaPlantType;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'farm_plant',
    'farm_seed',
    'farm_ui_views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalPlaceBlock('local_tasks_block');

    // Create/login a user with permission to access taxonomy pages and assets.
    $this->user = $this->createUser(['access content', 'view any asset']);
    $this->drupalLogin($this->user);

    $entity_type_manager = $this->container->get('entity_type.manager');
    $term_storage = $entity_type_manager->getStorage('taxonomy_term');

    // Create a "Oat" plant type term.
    $oat_plant_type = $term_storage->create([
      'name' => 'Oat',
      'vid' => 'plant_type',
    ]);
    $oat_plant_type->save();

    // Create a oat plant.
    Asset::create([
      'name' => 'Pringle\'s Progress Oat Planting',
      'type' => 'plant',
      'plant_type' => ['target_id' => $oat_plant_type->id()],
    ])->save();

    // Create a "Fava Bean" plant type term.
    $this->favaPlantType = $term_storage->create([
      'name' => 'Fava Bean',
      'vid' => 'plant_type',
    ]);
    $this->favaPlantType->save();

    // Create a fava plant.
    Asset::create([
      'name' => 'Red Flowering Fava Planting',
      'type' => 'plant',
      'plant_type' => ['target_id' => $this->favaPlantType->id()],
    ])->save();

    // Create a fava seed.
    Asset::create([
      'name' => 'Red Flowering Fava Seeds',
      'type' => 'seed',
      'plant_type' => ['target_id' => $this->favaPlantType->id()],
    ])->save();
  }

  /**
   * Test that the asset view task links appear on taxonomy term pages.
   */
  public function testTaxonomyTermAssetTaskTabsAppear() {
    $fava_term_url = 'taxonomy/term/' . $this->favaPlantType->id();

    $this->drupalGet($fava_term_url);
    $this->assertSession()->statusCodeEquals(200);

    $get_array_of_link_text_by_url = function ($elems) {
      $result = [];
      foreach ($elems as $elem) {
        $result[$elem->getAttribute('href')] = $elem->getText();
      }
      return $result;
    };

    // Select links from the first ul inside layout-container.
    $primary_tab_links = $get_array_of_link_text_by_url($this->xpath('(//div[@class="layout-container"]//ul)[1]/li/a'));

    $assert_has_link = function ($elements, $url, $label) {
      $this->assertArrayHasKey($url, $elements, "No link exists with url '$url' among: " . print_r($elements, TRUE));

      $this->assertEquals($label, $elements[$url], "Link label not as expected.");
    };

    $assert_has_link($primary_tab_links, "/$fava_term_url/assets", 'Assets');

    $this->drupalGet("$fava_term_url/assets/all");
    $this->assertSession()->statusCodeEquals(200);

    // Select links from the second ul inside layout-container.
    $secondary_tab_links = $get_array_of_link_text_by_url($this->xpath('(//div[@class="layout-container"]//ul)[2]/li/a'));

    $this->assertCount(3, $secondary_tab_links, 'Only 3 secondary tabs appear.');

    $assert_has_link($secondary_tab_links, "/$fava_term_url/assets", 'All(active tab)');
    $assert_has_link($secondary_tab_links, "/$fava_term_url/assets/plant", 'Plant');
    $assert_has_link($secondary_tab_links, "/$fava_term_url/assets/seed", 'Seed');
  }

  /**
   * Test that the views of assets for terms show the correct assets.
   */
  public function testTaxonomyTermAssetViews() {
    $fava_term_url = 'taxonomy/term/' . $this->favaPlantType->id();

    $this->drupalGet("$fava_term_url/assets/all");
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextContains('Red Flowering Fava Planting');
    $this->assertSession()->pageTextContains('Red Flowering Fava Seeds');
    $this->assertSession()->pageTextNotContains('Pringle\'s Progress Oat Planting');

    $this->drupalGet("$fava_term_url/assets/plant");
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextContains('Red Flowering Fava Planting');
    $this->assertSession()->pageTextNotContains('Red Flowering Fava Seeds');
    $this->assertSession()->pageTextNotContains('Pringle\'s Progress Oat Planting');

    $this->drupalGet("$fava_term_url/assets/seed");
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextNotContains('Red Flowering Fava Planting');
    $this->assertSession()->pageTextContains('Red Flowering Fava Seeds');
    $this->assertSession()->pageTextNotContains('Pringle\'s Progress Oat Planting');
  }

}
