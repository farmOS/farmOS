<?php

namespace Drupal\farm_input\Plugin\views\filter;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Plugin\views\filter\TaxonomyIndexTid;

/**
 * Filter handler for log quantity material type terms.
 *
 * @ViewsFilter("log_quantity_material_type")
 */
class LogQuantityMaterialType extends TaxonomyIndexTid {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    // Hard code the material_type vocabulary.
    $options['vid'] = ['default' => 'material_type'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildExtraOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildExtraOptionsForm($form, $form_state);
    // Remove the vocabulary form element, use the hard coded default.
    unset($form['vid']);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {

    // Bail if there are no filter values.
    if (count($this->value) == 0) {
      return;
    }
    elseif (count($this->value) == 1) {
      // Sometimes $this->value is an array with a single element so convert it.
      // @see TaxonomyIndexTidDepth::query().
      if (is_array($this->value)) {
        $this->value = current($this->value);
      }
      $operator = '=';
    }
    else {
      $operator = 'IN';
    }

    // Build a subquery to find logs that reference a quantity with the
    // specified material type.
    $subquery = Database::getConnection()->select('log', 'l');
    $subquery->addField('l', 'id');
    $subquery->innerJoin('log__quantity', 'lq', 'l.id = lq.entity_id');
    $subquery->innerJoin('quantity__material_type', 'qmt', 'lq.quantity_target_id = qmt.entity_id AND lq.quantity_target_revision_id = qmt.revision_id');
    $subquery->condition('qmt.material_type_target_id', $this->value, $operator);

    // Get the table alias for log_field_data.
    $this->tableAlias = $this->query->ensureTable($this->view->storage->get('base_table'));

    // Use the subquery in a condition on the views query to prevent duplicates.
    $this->query->addWhere($this->options['group'], "$this->tableAlias.id", $subquery, 'IN');
  }

}
