<?php

namespace Drupal\farm_import_csv\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\migrate_source_ui\Form\MigrateSourceUiForm;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
   * Get the title of the migration.
   *
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Returns the migration label.
   */
  public function getTitle(string $migration_id) {
    $migration_label = $this->pluginManagerMigration->getDefinition($migration_id)['label'];
    return $this->t('Import @label', ['@label' => $migration_label]);
  }

  /**
   * Checks access for a specific CSV importer.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, string $migration_id) {

    // Check access based on third party settings in the migration.
    if ($this->pluginManagerMigration->hasDefinition($migration_id)) {
      $importer = $this->pluginManagerMigration->getDefinition($migration_id);
      if ($importer['source']['plugin'] == 'csv' && $importer['migration_group'] == 'farm_import_csv') {
        $permissions = [];
        if (!empty($importer['third_party_settings']['farm_import_csv']['access']['permissions'])) {
          $permissions = $importer['third_party_settings']['farm_import_csv']['access']['permissions'];
        }
        return AccessResult::allowedIfHasPermissions($account, $permissions);
      }
    }

    // Raise 404 if the migration does not exist.
    throw new ResourceNotFoundException();
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

    // Rename the submit button to "Import" and set the weight to 100.
    $form['import']['#value'] = $this->t('Import');
    $form['import']['#weight'] = 100;

    // Load the migration definition.
    $migration = $this->pluginManagerMigration->getDefinition($migration_id);

    // Show column descriptions, if available.
    if (!empty($migration['third_party_settings']['farm_import_csv']['columns'])) {

      // Create a collapsed fieldset.
      $form['columns'] = [
        '#type' => 'details',
        '#title' => $this->t('CSV Columns'),
      ];

      // Show a description of the columns.
      $items = [];
      foreach ($migration['third_party_settings']['farm_import_csv']['columns'] as $info) {
        if (!empty($info['name'])) {
          $item = '<strong>' . $info['name'] . '</strong>';
          if (!empty($info['description'])) {
            $item .= ': ' . $this->t($info['description']);
          }
          $items[] = Markup::create($item);
        }
      }
      $form['columns']['descriptions'] = [
        '#theme' => 'item_list',
        '#items' => $items,
      ];
    }

    return $form;
  }

}
