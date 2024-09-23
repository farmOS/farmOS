<?php

declare(strict_types=1);

namespace Drupal\farm_timeline\TypedData;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\TypedData\ComplexDataDefinitionBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;

/**
 * Timeline task definition.
 */
class TimelineTaskDefinition extends ComplexDataDefinitionBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    if (empty($this->propertyDefinitions)) {

      $this->propertyDefinitions['id'] = DataDefinition::create('string')
        ->setLabel($this->t('ID'))
        ->setRequired(TRUE);

      $this->propertyDefinitions['resource_id'] = DataDefinition::create('string')
        ->setLabel($this->t('Resource ID'))
        ->setRequired(TRUE);

      $this->propertyDefinitions['label'] = DataDefinition::create('string')
        ->setLabel($this->t('Label'));

      $this->propertyDefinitions['link_url'] = DataDefinition::create('uri')
        ->setLabel($this->t('Link URL'));

      $this->propertyDefinitions['edit_url'] = DataDefinition::create('uri')
        ->setLabel($this->t('Edit URL'));

      $this->propertyDefinitions['start'] = DataDefinition::create('timestamp')
        ->setLabel($this->t('Start Time'))
        ->setRequired(TRUE);

      $this->propertyDefinitions['end'] = DataDefinition::create('timestamp')
        ->setLabel($this->t('End Time'))
        ->setRequired(TRUE);

      $this->propertyDefinitions['draggable'] = DataDefinition::create('boolean')
        ->setLabel($this->t('Enable dragging'))
        ->addConstraint('NotNull');

      $this->propertyDefinitions['resizable'] = DataDefinition::create('boolean')
        ->setLabel($this->t('Enable resizing'))
        ->addConstraint('NotNull');

      $this->propertyDefinitions['meta'] = DataDefinition::create('any')
        ->setLabel($this->t('Meta'));

      $this->propertyDefinitions['classes'] = ListDataDefinition::create('string')
        ->setLabel($this->t('Classes'));

    }
    return $this->propertyDefinitions;
  }

}
