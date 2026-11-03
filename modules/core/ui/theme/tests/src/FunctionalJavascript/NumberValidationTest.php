<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_theme\FunctionalJavascript;

use Behat\Mink\Element\NodeElement;
use Drupal\Tests\farm_test\FunctionalJavascript\FarmWebDriverTestBase;
use Drupal\field\Entity\FieldConfig;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests numeric input in content forms with vertical tabs.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class NumberValidationTest extends FarmWebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'toolbar',
    'farm_ui_theme_test',
    'farm_plant_type',
    'farm_transplanting',
  ];

  /**
   * The plant type being edited.
   */
  protected TermInterface $term;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->term = Term::create([
      'vid' => 'plant_type',
      'name' => 'Numeric validation plant',
      'transplant_days' => 10,
      'maturity_days' => 10,
      'harvest_days' => 10,
    ]);
    $this->term->save();
    $this->drupalLogin($this->createUser(['administer taxonomy', 'access toolbar']));
  }

  /**
   * Malformed numbers cannot silently replace saved values with empty strings.
   */
  public function testMalformedNumbers(): void {
    foreach (['transplant_days', 'maturity_days', 'harvest_days'] as $field) {
      foreach (['20-30', '2-'] as $value) {
        $previous = $this->savedValue($field);
        $this->openForm();
        $input = $this->typeNumber($field, $value);
        $this->assertTrue($this->numberProperty($field, 'validity.badInput'));
        $this->assertSame('', $input->getValue());
        $this->saveForm();
        $this->waitForNumberFocus($field);
        $this->assertSession()->addressEquals('taxonomy/term/' . $this->term->id() . '/edit');
        $this->assertSame($previous, $this->savedValue($field));
        $this->assertTrue($this->numberProperty($field, 'matches(":focus")'));
        $this->assertNotEmpty($this->numberProperty($field, 'validationMessage'));

        // A corrected retry must not be blocked by duplicate-submit protection.
        $this->typeNumber($field, '12');
        $this->saveForm();
        $this->waitForSaved();
        $this->assertSession()->addressEquals('taxonomy/term/' . $this->term->id());
        $this->assertSame('12', $this->savedValue($field));
      }
    }
  }

  /**
   * The tab containing malformed input opens before the field receives focus.
   */
  public function testMalformedNumberInHiddenTab(): void {
    $this->moveToReferenceTab('maturity_days');
    $this->openForm();
    $this->getSession()->getPage()->clickLink('Reference');
    $input = $this->typeNumber('maturity_days', '2-');
    $this->getSession()->getPage()->clickLink('General');
    $this->assertFalse($input->isVisible());

    $this->saveForm();
    $this->waitForNumberFocus('maturity_days');

    $this->assertSession()->addressEquals('taxonomy/term/' . $this->term->id() . '/edit');
    $this->assertTrue($input->isVisible());
    $this->assertTrue($this->numberProperty('maturity_days', 'matches(":focus")'));
    $this->assertSame('10', $this->savedValue('maturity_days'));
  }

  /**
   * Empty, numeric, range, and hidden required fields retain server validation.
   */
  public function testOtherValidation(): void {
    $this->openForm();
    foreach (['transplant_days', 'maturity_days', 'harvest_days'] as $field) {
      $this->typeNumber($field, '');
    }
    $this->saveForm();
    $this->waitForSaved();
    foreach (['transplant_days', 'maturity_days', 'harvest_days'] as $field) {
      $this->assertNull($this->savedValue($field));
    }

    $this->openForm();
    foreach (['transplant_days', 'maturity_days', 'harvest_days'] as $field) {
      $this->typeNumber($field, '12');
    }
    $this->saveForm();
    $this->waitForSaved();
    foreach (['transplant_days', 'maturity_days', 'harvest_days'] as $field) {
      $this->assertSame('12', $this->savedValue($field));
    }

    // Let Drupal validate numeric ranges rather than enabling all HTML5 checks.
    FieldConfig::load('taxonomy_term.plant_type.harvest_days')->setSetting('max', 30)->save();
    foreach (['0', '31'] as $value) {
      $this->openForm();
      $input = $this->typeNumber('harvest_days', $value);
      $this->assertSame($value, $input->getValue());
      $this->assertFalse($this->numberProperty('harvest_days', 'validity.badInput'));
      $this->saveForm();
      $this->assertNotNull($this->assertSession()->waitForElement('css', '.messages--error'));
      $this->assertSame('12', $this->savedValue('harvest_days'));
    }

    $this->moveToReferenceTab('name');
    $this->openForm();
    $this->getSession()->getPage()->clickLink('Reference');
    $name = $this->assertSession()->fieldExists('name[0][value]');
    $name->setValue('');
    $this->getSession()->getPage()->clickLink('General');
    $this->assertFalse($name->isVisible());
    $this->saveForm();
    $this->assertNotNull($this->assertSession()->waitForElement('css', '.messages--error'));
    $this->assertSession()->pageTextContains('Name field is required.');
  }

  /**
   * Opens the term form.
   */
  protected function openForm(): void {
    $this->drupalGet('taxonomy/term/' . $this->term->id() . '/edit');
    $this->assertTrue($this->getSession()->evaluateScript('document.querySelector("form.taxonomy-term-form").noValidate'));
  }

  /**
   * Types with WebDriver so invalid input is not sanitized by JS assignment.
   */
  protected function typeNumber(string $field, string $value): NodeElement {
    $input = $this->assertSession()->fieldExists($field . '[0][value]');
    /** @var \Drupal\FunctionalJavascriptTests\DrupalSelenium2Driver $driver */
    $driver = $this->getSession()->getDriver();
    $element = $driver->getWebDriverSession()->element('xpath', $input->getXpath());
    $element->clear();
    if ($value !== '') {
      $element->postValue(['text' => $value]);
    }
    return $input;
  }

  /**
   * Reads a number input property without changing its native input state.
   */
  protected function numberProperty(string $field, string $property): mixed {
    $selector = json_encode('input[name="' . $field . '[0][value]"]');
    return $this->getSession()->evaluateScript('document.querySelector(' . $selector . ').' . $property);
  }

  /**
   * Waits for the submit guard to return focus to the malformed input.
   */
  protected function waitForNumberFocus(string $field): void {
    $selector = json_encode('input[name="' . $field . '[0][value]"]');
    $this->assertJsCondition('document.activeElement === document.querySelector(' . $selector . ')');
  }

  /**
   * Waits for Gin's mirrored Save action and the resulting page navigation.
   */
  protected function waitForSaved(): void {
    $path = json_encode('/taxonomy/term/' . $this->term->id());
    $this->assertJsCondition('location.pathname === ' . $path . ' && document.readyState === "complete"');
  }

  /**
   * Clicks the visible Gin Save action rather than its offscreen original.
   */
  protected function saveForm(): void {
    foreach ($this->getSession()->getPage()->findAll('css', 'input[type=submit],button') as $button) {
      if (($button->getAttribute('value') ?: trim($button->getText())) !== 'Save') {
        continue;
      }
      $xpath = json_encode($button->getXpath());
      $clickable = $this->getSession()->evaluateScript('(function(){const el=document.evaluate(' . $xpath . ',document,null,XPathResult.FIRST_ORDERED_NODE_TYPE,null).singleNodeValue;const r=el.getBoundingClientRect();const hit=document.elementFromPoint(r.x+r.width/2,r.y+r.height/2);return r.width>0&&r.height>20&&hit&&(hit===el||el.contains(hit));})()');
      if ($clickable) {
        $button->press();
        return;
      }
    }
    $this->fail('A visible Save control was not found.');
  }

  /**
   * Reads the saved value without retaining a cached entity.
   */
  protected function savedValue(string $field): ?string {
    $storage = $this->container->get('entity_type.manager')->getStorage('taxonomy_term');
    $storage->resetCache([$this->term->id()]);
    $value = $storage->load($this->term->id())->get($field)->value;
    return $value === NULL ? NULL : (string) $value;
  }

  /**
   * Puts a field in the initially hidden Reference tab.
   */
  protected function moveToReferenceTab(string $field): void {
    $this->container->get('state')->set('farm_ui_theme_test.reference_field', $field);
  }

}
