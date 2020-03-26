<?php

namespace Drupal\Tests\farm_asset\Functional;

use Drupal\farm_asset\Entity\FarmAsset;

/**
 * Tests the farm_asset CRUD.
 *
 * @group farm_asset
 */
class FarmAssetCRUDTest extends FarmAssetTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Fields are displayed correctly.
   */
  public function testFieldsVisibility() {
    $this->drupalGet('farm-asset/add/default');
    $this->assertResponse('200');
    $assert_session = $this->assertSession();
    $assert_session->fieldExists('name[0][value]');
    $assert_session->fieldExists('status');
    $assert_session->fieldExists('revision_log_message[0][value]');
    $assert_session->fieldExists('uid[0][target_id]');
    $assert_session->fieldExists('created[0][value][date]');
    $assert_session->fieldExists('created[0][value][time]');
  }

  /**
   * Create farm_asset entity.
   */
  public function testCreateFarmAsset() {
    $assert_session = $this->assertSession();
    $name = $this->randomMachineName();
    $edit = [
      'name[0][value]' => $name,
    ];

    $this->drupalPostForm('farm-asset/add/default', $edit, t('Save'));

    $result = \Drupal::entityTypeManager()
      ->getStorage('farm_asset')
      ->getQuery()
      ->range(0, 1)
      ->execute();
    $farm_asset_id = reset($result);
    $farm_asset = FarmAsset::load($farm_asset_id);
    $this->assertEquals($farm_asset->get('name')->value, $name, 'farm asset has been saved.');

    $assert_session->pageTextContains("Saved the $name farm asset.");
    $assert_session->pageTextContains($name);
  }

  /**
   * Display farm_asset entity.
   */
  public function testViewFarmAsset() {
    $edit = [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
      'done' => TRUE,
    ];
    $farm_asset = $this->createFarmAssetEntity($edit);
    $farm_asset->save();

    $this->drupalGet($farm_asset->toUrl('canonical'));
    $this->assertResponse(200);

    $this->assertText($edit['name']);
    $this->assertRaw(\Drupal::service('date.formatter')->format(\Drupal::time()->getRequestTime()));
  }

  /**
   * Edit farm_asset entity.
   */
  public function testEditFarmAsset() {
    $farm_asset = $this->createFarmAssetEntity();
    $farm_asset->save();

    $edit = [
      'name[0][value]' => $this->randomMachineName(),
    ];
    $this->drupalPostForm($farm_asset->toUrl('edit-form'), $edit, t('Save'));

    $this->assertText($edit['name[0][value]']);
  }

  /**
   * Delete farm_asset entity.
   */
  public function testDeleteFarmAsset() {
    $farm_asset = $this->createFarmAssetEntity();
    $farm_asset->save();

    $label = $farm_asset->getName();
    $farm_asset_id = $farm_asset->id();

    $this->drupalPostForm($farm_asset->toUrl('delete-form'), [], t('Delete'));
    $this->assertRaw(t('The @entity-type %label has been deleted.', [
      '@entity-type' => $farm_asset->getEntityType()->getSingularLabel(),
      '%label' => $label,
    ]));
    $this->assertNull(FarmAsset::load($farm_asset_id));
  }

}
