<?php

namespace Drupal\Tests\plan\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\plan\Entity\Plan;

/**
 * Tests the plan CRUD.
 *
 * @group farm
 */
class PlanCRUDTest extends PlanTestBase {

  use StringTranslationTrait;

  /**
   * Fields are displayed correctly.
   */
  public function testFieldsVisibility() {
    $this->drupalGet('plan/add/default');
    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);
    $assert_session->fieldExists('name[0][value]');
    $assert_session->fieldExists('status');
    $assert_session->fieldExists('revision_log_message[0][value]');
    $assert_session->fieldExists('uid[0][target_id]');
    $assert_session->fieldExists('created[0][value][date]');
    $assert_session->fieldExists('created[0][value][time]');
  }

  /**
   * Create plan entity.
   */
  public function testCreatePlan() {
    $assert_session = $this->assertSession();
    $name = $this->randomMachineName();
    $edit = [
      'name[0][value]' => $name,
    ];

    $this->drupalGet('plan/add/default');
    $this->submitForm($edit, $this->t('Save'));

    $result = \Drupal::entityTypeManager()
      ->getStorage('plan')
      ->getQuery()
      ->accessCheck(TRUE)
      ->range(0, 1)
      ->execute();
    $plan_id = reset($result);
    $plan = Plan::load($plan_id);
    $this->assertEquals($plan->get('name')->value, $name, 'plan has been saved.');

    $assert_session->pageTextContains("Saved plan: $name");
    $assert_session->pageTextContains($name);
  }

  /**
   * Display plan entity.
   */
  public function testViewPlan() {
    $edit = [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
    ];
    $plan = $this->createPlanEntity($edit);
    $plan->save();

    $this->drupalGet($plan->toUrl('canonical'));
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextContains($edit['name']);
    $this->assertSession()->responseContains(\Drupal::service('date.formatter')->format(\Drupal::time()->getRequestTime()));
  }

  /**
   * Edit plan entity.
   */
  public function testEditPlan() {
    $plan = $this->createPlanEntity();
    $plan->save();

    $edit = [
      'name[0][value]' => $this->randomMachineName(),
    ];
    $this->drupalGet($plan->toUrl('edit-form'));
    $this->submitForm($edit, $this->t('Save'));

    $this->assertSession()->pageTextContains($edit['name[0][value]']);
  }

  /**
   * Delete plan entity.
   */
  public function testDeletePlan() {
    $plan = $this->createPlanEntity();
    $plan->save();

    $label = $plan->getName();
    $plan_id = $plan->id();

    $this->drupalGet($plan->toUrl('delete-form'));
    $this->submitForm([], $this->t('Delete'));
    $this->assertSession()->responseContains($this->t('The @entity-type %label has been deleted.', [
      '@entity-type' => $plan->getEntityType()->getSingularLabel(),
      '%label' => $label,
    ]));
    $this->assertNull(Plan::load($plan_id));
  }

  /**
   * Plan archiving.
   */
  public function testArchivePlan() {
    $plan = $this->createPlanEntity();
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'active', 'New plans are active by default');
    $this->assertNull($plan->getArchivedTime(), 'Archived timestamp is null by default');

    $plan->get('status')->first()->applyTransitionById('archive');
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'archived', 'Plans can be archived');
    $this->assertNotNull($plan->getArchivedTime(), 'Archived timestamp is saved');

    $plan->get('status')->first()->applyTransitionById('to_active');
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'active', 'Plans can be made active');
    $this->assertNull($plan->getArchivedTime(), 'Plan made active has a null timestamp');

    $plan->get('status')->first()->applyTransitionById('archive');
    $plan->setArchivedTime('2021-07-17T19:45:49+00:00');
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'archived', 'Plans can be archived with explicit timestamp');
    $this->assertEquals($plan->getArchivedTime(), '2021-07-17T19:45:49+00:00', 'Explicit archived timestamp is saved');
  }

  /**
   * Plan archiving/unarchiving via timestamp.
   */
  public function testArchivePlanViaTimestamp() {
    $plan = $this->createPlanEntity();
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'active', 'New plans are active by default');
    $this->assertNull($plan->getArchivedTime(), 'Archived timestamp is null by default');

    $plan->setArchivedTime('2021-07-17T19:45:49+00:00');
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'archived', 'Plans can be archived');
    $this->assertEquals($plan->getArchivedTime(), '2021-07-17T19:45:49+00:00', 'Archived timestamp is saved');

    $plan->setArchivedTime(NULL);
    $plan->save();

    $this->assertEquals($plan->get('status')->first()->getString(), 'active', 'Plans can be made active');
    $this->assertNull($plan->getArchivedTime(), 'Plan made active has a null timestamp');
  }

}
