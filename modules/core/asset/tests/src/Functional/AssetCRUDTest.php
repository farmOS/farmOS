<?php

namespace Drupal\Tests\asset\Functional;

use Drupal\asset\Entity\Asset;

/**
 * Tests the asset CRUD.
 *
 * @group asset
 */
class AssetCRUDTest extends AssetTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Fields are displayed correctly.
   */
  public function testFieldsVisibility() {
    $this->drupalGet('asset/add/default');
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
   * Create asset entity.
   */
  public function testCreateAsset() {
    $assert_session = $this->assertSession();
    $name = $this->randomMachineName();
    $edit = [
      'name[0][value]' => $name,
    ];

    $this->drupalPostForm('asset/add/default', $edit, t('Save'));

    $result = \Drupal::entityTypeManager()
      ->getStorage('asset')
      ->getQuery()
      ->range(0, 1)
      ->execute();
    $asset_id = reset($result);
    $asset = Asset::load($asset_id);
    $this->assertEquals($asset->get('name')->value, $name, 'asset has been saved.');

    $assert_session->pageTextContains("Saved the $name asset.");
    $assert_session->pageTextContains($name);
  }

  /**
   * Display asset entity.
   */
  public function testViewAsset() {
    $edit = [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
      'done' => TRUE,
    ];
    $asset = $this->createAssetEntity($edit);
    $asset->save();

    $this->drupalGet($asset->toUrl('canonical'));
    $this->assertResponse(200);

    $this->assertText($edit['name']);
    $this->assertRaw(\Drupal::service('date.formatter')->format(\Drupal::time()->getRequestTime()));
  }

  /**
   * Edit asset entity.
   */
  public function testEditAsset() {
    $asset = $this->createAssetEntity();
    $asset->save();

    $edit = [
      'name[0][value]' => $this->randomMachineName(),
    ];
    $this->drupalPostForm($asset->toUrl('edit-form'), $edit, t('Save'));

    $this->assertText($edit['name[0][value]']);
  }

  /**
   * Delete asset entity.
   */
  public function testDeleteAsset() {
    $asset = $this->createAssetEntity();
    $asset->save();

    $label = $asset->getName();
    $asset_id = $asset->id();

    $this->drupalPostForm($asset->toUrl('delete-form'), [], t('Delete'));
    $this->assertRaw(t('The @entity-type %label has been deleted.', [
      '@entity-type' => $asset->getEntityType()->getSingularLabel(),
      '%label' => $label,
    ]));
    $this->assertNull(Asset::load($asset_id));
  }

}
