<?php

namespace Drupal\farm_quick_test\Plugin\QuickForm;

/**
 * Test quick form that requires a configuration entity.
 *
 * @QuickForm(
 *   id = "requires_entity_test",
 *   label = @Translation("Test requiresEntity quick form"),
 *   description = @Translation("Test requiresEntity quick form description."),
 *   helpText = @Translation("Test requiresEntity quick form help text."),
 *   permissions = {
 *     "create test log",
 *   },
 *   requiresEntity = True
 * )
 */
class RequiresEntityTest extends Test {

}
