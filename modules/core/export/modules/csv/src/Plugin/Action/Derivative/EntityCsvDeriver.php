<?php

namespace Drupal\farm_export_csv\Plugin\Action\Derivative;

use Drupal\Core\Action\Plugin\Action\Derivative\EntityActionDeriverBase;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Provides an action deriver for the CSV action.
 *
 * @see \Drupal\farm_export_csv\Plugin\Action\EntityCsv
 */
class EntityCsvDeriver extends EntityActionDeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (empty($this->derivatives)) {
      $definitions = [];
      foreach ($this->getApplicableEntityTypes() as $entity_type_id => $entity_type) {
        $definition = $base_plugin_definition;
        $definition['type'] = $entity_type_id;
        $definition['label'] = $this->t('Export @entity_type CSV', ['@entity_type' => $entity_type->getSingularLabel()]);
        $definition['confirm_form_route_name'] = 'entity.' . $entity_type->id() . '.csv_form';
        $definitions[$entity_type_id] = $definition;
      }
      $this->derivatives = $definitions;
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);

  }

  /**
   * {@inheritdoc}
   */
  protected function isApplicable(EntityTypeInterface $entity_type) {
    return in_array($entity_type->id(), ['log', 'asset']);
  }

}
