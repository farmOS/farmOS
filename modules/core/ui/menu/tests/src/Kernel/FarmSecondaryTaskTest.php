<?php

namespace Drupal\Tests\farm_ui_menu\Kernel;

use Drupal\farm_ui_menu\Menu\EntityTypeLabelLocalTask;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests farmOS secondary task links.
 *
 * @group farm
 */
class FarmSecondaryTaskTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'data_stream',
    'entity',
    'farm_entity',
    'farm_ui_menu',
    'log',
    'plan',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('data_stream');
    $this->installEntitySchema('log');
    $this->installEntitySchema('plan');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installConfig([
      'system',
    ]);
  }

  /**
   * Test farmOS fields defined in hook_entity_base_field_info().
   */
  public function testSecondaryTaskDefinitions() {

    /** @var \Drupal\Core\Menu\LocalTaskManagerInterface $local_task_manager */
    $local_task_manager = \Drupal::service('plugin.manager.menu.local_task');
    $definitions = $local_task_manager->getDefinitions();

    $entity_types_revisions = [
      'asset' => TRUE,
      'data_stream' => FALSE,
      'log' => TRUE,
      'plan' => TRUE,
      'taxonomy_term' => FALSE,
    ];
    foreach ($entity_types_revisions as $entity_type_id => $supports_revision) {

      // Build the expected parent ID.
      $parent_id = "entity.entity_tasks:entity.$entity_type_id.canonical";

      // Assert correct primary canonical task.
      $this->assertIsArray($definitions[$parent_id]);
      $this->assertEquals("entity.$entity_type_id.canonical", $definitions[$parent_id]['route_name']);
      $this->assertEquals(EntityTypeLabelLocalTask::class, $definitions[$parent_id]['class']);

      // Assert correct secondary default task.
      $secondary_id = "entity.entity_tasks:entity.$entity_type_id.canonical.secondary";
      $this->assertIsArray($definitions[$secondary_id]);
      $this->assertEquals("entity.$entity_type_id.canonical", $definitions[$secondary_id]['route_name']);
      $this->assertEquals($parent_id, $definitions[$secondary_id]['parent_id']);

      // Assert correct secondary edit task.
      $edit_id = "entity.entity_tasks:entity.$entity_type_id.edit_form";
      $this->assertIsArray($definitions[$edit_id]);
      $this->assertEquals("entity.$entity_type_id.edit_form", $definitions[$edit_id]['route_name']);
      $this->assertEquals($parent_id, $definitions[$edit_id]['parent_id']);

      // Assert correct secondary revision task.
      if ($supports_revision) {
        $revision_id = "entity.entity_tasks:entity.$entity_type_id.version_history";
        $this->assertIsArray($definitions[$revision_id]);
        $this->assertEquals("entity.$entity_type_id.version_history", $definitions[$revision_id]['route_name']);
        $this->assertEquals($parent_id, $definitions[$revision_id]['parent_id']);
      }
    }
  }

}
