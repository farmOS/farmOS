<?php

namespace Drupal\farm_import_csv\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\migrate_source_ui\Form\MigrateSourceUiForm;

/**
 * Provides the CSV import form.
 */
class CsvImportForm extends MigrateSourceUiForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_import_csv';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $migration_id = NULL) {
    $form = parent::buildForm($form, $form_state);

    // Hard-code and hide the dropdown of migrations.
    $form['migrations']['#type'] = 'value';
    $form['migrations']['#value'] = $migration_id;

    // Remove the option to update existing records.
    // @todo https://www.drupal.org/project/farm/issues/2968909
    $form['update_existing_records']['#type'] = 'value';
    $form['update_existing_records']['#value'] = FALSE;

    // Rename the submit button to "Import".
    $form['import']['#value'] = $this->t('Import');

    return $form;
  }

}
