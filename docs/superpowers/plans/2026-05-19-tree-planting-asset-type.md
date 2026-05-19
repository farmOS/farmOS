# Tree Planting Asset Type Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a `tree_planting` asset bundle to `farm_syntropic` that lets operators record a row or block of same-species trees in a single form submission, with bidirectional navigation to individual Tree assets.

**Architecture:** A new `#[AssetType(id: 'tree_planting')]` PHP plugin class following the exact `Tree.php` / `Infrastructure.php` pattern, backed by a config entity YAML with a fresh UUID. A typed `parent_planting` entity-reference field is added to `Tree::buildFieldDefinitions()` using the `target_bundle => 'tree_planting'` shorthand — which FarmFieldFactory already honors for `target_type => 'asset'` via the `case 'asset':` branch in `modifyEntityReferenceField()` (lines 354-367 of `FarmFieldFactory.php`). The forward relationship (Planting → its Trees) is exposed through a Drupal View config entity shipped in `config/install/`, keeping all implementation declarative and PHP-free.

**Tech Stack:** Drupal 11, PHP 8.4, farmOS 4.x, FarmFieldFactory, geofield

---

## File Structure

### New files

| File | Purpose |
|---|---|
| `modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php` | Asset type plugin — 8-field `buildFieldDefinitions()` |
| `modules/farm_syntropic/config/install/asset.type.tree_planting.yml` | Config entity for the bundle (fresh UUID) |
| `modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml` | Drupal View — "Trees in this Planting" embedded on the Planting page |

### Modified files

| File | Change summary |
|---|---|
| `modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php` | Add `parent_planting` field to `$field_info` array |
| `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php` | 4 new test methods; update 2 existing assertions |
| `.github/workflows/smoke-farm-syntropic.yml` | Extend bundle check + 2 new steps |
| `docs/workflows/tree-inventory-data-entry.md` | New "When to use Tree Planting first" section; demote per-tree path |
| `CHANGELOG.md` | Added/Changed entries under `[Unreleased]` |

---

## Open Question Resolutions

### Open Question #1 — FarmFieldFactory asset-bundle constraint

**Decision: the `target_bundle` shorthand works for `target_type => 'asset'`. No config override YAML is needed.**

Evidence: `docker/www/web/profiles/farm/modules/core/field/src/FarmFieldFactory.php` lines 354-367, `case 'asset':` branch of `modifyEntityReferenceField()`:

```php
case 'asset':
  if (!empty($options['target_bundle'])) {
    $handler = 'default:asset';
    $handler_settings = [
      'target_bundles' => [
        $options['target_bundle'] => $options['target_bundle'],
      ],
      ...
    ];
  }
```

Structurally identical to the `case 'taxonomy_term':` branch — both check `!empty($options['target_bundle'])` and populate `handler_settings['target_bundles']`. Passing `'target_bundle' => 'tree_planting'` in `Tree::buildFieldDefinitions()` produces a field constrained to `tree_planting` assets via the `default:asset` handler with a populated `target_bundles` array. No `config/install/field.field.asset.tree.parent_planting.yml` override is required.

### Open Question #2 — Bidirectional display (Tree Planting → Trees)

**Decision: Drupal View config entity shipped in `config/install/views.view.tree_planting_trees.yml`.**

A View is declarative, config-exportable, overridable by site builders in the Views UI, and avoids adding a `.module` file. A `hook_entity_view_alter` implementation would require procedural PHP and cannot be overridden without code. Shipping Views in `config/install/` is standard Drupal/farmOS practice.

### Open Question #3 — Smoke test auth scope

No extra rebuild step is needed. The existing smoke test sequence already runs `drush en farm_syntropic --yes`, which triggers `ConfigInstaller` to import all YAMLs in `config/install/` (including the new `asset.type.tree_planting.yml`). Bundle field definitions are derived at runtime from the plugin class — they do not require a separate cache rebuild beyond what `drush en` already performs.

---

## Tasks

### Task 1 — Generate UUID and create `asset.type.tree_planting.yml`

- [ ] Generate a UUID. Run locally:
  ```
  php -r "echo sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0x0fff, 0x4fff)|0x4000, mt_rand(0x8000, 0xbfff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)); echo PHP_EOL;"
  ```
  Save the output — call it `$TREE_PLANTING_UUID`.

- [ ] Create `modules/farm_syntropic/config/install/asset.type.tree_planting.yml` with the following content, substituting the generated UUID:
  ```yaml
  uuid: $TREE_PLANTING_UUID
  langcode: en
  status: true
  dependencies:
    enforced:
      module:
        - farm_syntropic
  id: tree_planting
  label: 'Tree Planting'
  description: 'A row or block of trees planted as a batch. Parent record for individual Tree assets.'
  new_revision: true
  ```

- [ ] Verify YAML syntax:
  ```
  python3 -c "import yaml; yaml.safe_load(open('modules/farm_syntropic/config/install/asset.type.tree_planting.yml'))" && echo "OK"
  ```
  Expected: `OK`

### Task 2 — Create `TreePlanting.php` plugin skeleton

- [ ] Create `modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php`:
  ```php
  <?php

  declare(strict_types=1);

  namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

  use Drupal\Core\StringTranslation\TranslatableMarkup;
  use Drupal\farm_entity\Attribute\AssetType;
  use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

  /**
   * Provides the tree planting asset type.
   */
  #[AssetType(
    id: 'tree_planting',
    label: new TranslatableMarkup('Tree Planting'),
  )]
  class TreePlanting extends FarmAssetType {

    /**
     * {@inheritdoc}
     */
    public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();
      return $fields;
    }

  }
  ```

- [ ] Syntax-check:
  ```
  php -l modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php
  ```
  Expected: `No syntax errors detected in ...TreePlanting.php`

### Task 3 — Write failing kernel tests (red phase)

Edit `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php`. Add four new test methods after `testValidTreeMeasurementsPass()`. Do not touch existing methods yet.

- [ ] Add `testTreePlantingBundleRegistered()`:
  ```php
  /**
   * tree_planting bundle registers from config/install.
   */
  public function testTreePlantingBundleRegistered(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('asset_type');
    $bundle = $storage->load('tree_planting');
    $this->assertNotNull($bundle, "Asset bundle 'tree_planting' should be registered");
    $this->assertSame('Tree Planting', $bundle->label());
  }
  ```

- [ ] Add `testTreePlantingFieldsRegistered()`:
  ```php
  /**
   * All 8 TreePlanting custom fields exist.
   */
  public function testTreePlantingFieldsRegistered(): void {
    $expected = [
      'species', 'variety', 'tree_count', 'spacing_m',
      'geometry', 'planting_date', 'source', 'notes',
    ];
    $actual = array_keys($this->fieldManager->getFieldDefinitions('asset', 'tree_planting'));
    $missing = array_diff($expected, $actual);
    $this->assertEmpty(
      $missing,
      'Missing TreePlanting fields: ' . implode(', ', $missing),
    );
  }
  ```

- [ ] Add `testTreeParentPlantingFieldRegistered()`:
  ```php
  /**
   * The parent_planting field exists on the Tree bundle and is bundle-constrained.
   */
  public function testTreeParentPlantingFieldRegistered(): void {
    $fields = $this->fieldManager->getFieldDefinitions('asset', 'tree');
    $this->assertArrayHasKey(
      'parent_planting',
      $fields,
      "Tree bundle should have a 'parent_planting' field",
    );
    $definition = $fields['parent_planting'];
    $this->assertSame('asset', $definition->getSetting('target_type'));
    $handler_settings = $definition->getSetting('handler_settings');
    $this->assertArrayHasKey('target_bundles', $handler_settings);
    $this->assertSame(
      ['tree_planting' => 'tree_planting'],
      $handler_settings['target_bundles'],
      'parent_planting handler_settings must constrain to tree_planting bundle',
    );
  }
  ```

- [ ] Add `testTreePlantingTreeCountValidation()`:
  ```php
  /**
   * tree_count = 0 produces a violation; tree_count = 1 passes.
   */
  public function testTreePlantingTreeCountValidation(): void {
    $planting_zero = \Drupal\asset\Entity\Asset::create([
      'type' => 'tree_planting',
      'name' => 'Validation probe - zero count',
      'tree_count' => 0,
    ]);
    $violations = $planting_zero->validate();
    $this->assertGreaterThan(
      0,
      $violations->getByField('tree_count')->count(),
      'tree_count = 0 should produce a validation violation (min is 1)',
    );

    $planting_one = \Drupal\asset\Entity\Asset::create([
      'type' => 'tree_planting',
      'name' => 'Validation probe - valid count',
      'tree_count' => 1,
    ]);
    $violations = $planting_one->validate();
    $this->assertSame(
      0,
      $violations->getByField('tree_count')->count(),
      'tree_count = 1 should not produce a validation violation',
    );
  }
  ```

- [ ] Run the new tests — confirm they FAIL:
  ```
  docker compose -f docker/docker-compose.farm-syntropic.yml exec www \
    php /opt/drupal/vendor/bin/phpunit \
    --filter "testTreePlantingFieldsRegistered" \
    web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```
  Expected: FAIL with `Missing TreePlanting fields: species, variety, tree_count, ...`

### Task 4 — Implement all 8 fields in `TreePlanting::buildFieldDefinitions()`

- [ ] Replace the stub method in `TreePlanting.php` so the full class reads:
  ```php
  <?php

  declare(strict_types=1);

  namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

  use Drupal\Core\StringTranslation\TranslatableMarkup;
  use Drupal\farm_entity\Attribute\AssetType;
  use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

  /**
   * Provides the tree planting asset type.
   */
  #[AssetType(
    id: 'tree_planting',
    label: new TranslatableMarkup('Tree Planting'),
  )]
  class TreePlanting extends FarmAssetType {

    /**
     * {@inheritdoc}
     */
    public function buildFieldDefinitions() {
      $fields = parent::buildFieldDefinitions();

      $field_info = [
        'species' => [
          'type' => 'entity_reference',
          'label' => $this->t('Species'),
          'description' => $this->t('Species planted in this batch (e.g., Castanea dentata).'),
          'target_type' => 'taxonomy_term',
          'target_bundle' => 'plant_type',
          'auto_create' => TRUE,
          'required' => TRUE,
          'multiple' => FALSE,
          'weight' => ['form' => -90, 'view' => -90],
        ],
        'variety' => [
          'type' => 'string',
          'label' => $this->t('Variety'),
          'description' => $this->t('Cultivar or variety name for the batch.'),
          'weight' => ['form' => -85, 'view' => -85],
        ],
        'tree_count' => [
          'type' => 'integer',
          'label' => $this->t('Tree count'),
          'description' => $this->t('Declared number of trees in this planting. Update manually if trees die or are removed.'),
          'required' => TRUE,
          'min' => 1,
          'weight' => ['form' => -80, 'view' => -80],
        ],
        'spacing_m' => [
          'type' => 'decimal',
          'label' => $this->t('Spacing (m)'),
          'description' => $this->t('On-center spacing in meters (for row plantings; leave blank for blocks).'),
          'precision' => 6,
          'scale' => 2,
          'min' => 0,
          'weight' => ['form' => -75, 'view' => -75],
        ],
        'planting_date' => [
          'type' => 'timestamp',
          'label' => $this->t('Planting date'),
          'description' => $this->t('Date the batch was installed in the ground.'),
          'weight' => ['form' => -70, 'view' => -70],
        ],
        'source' => [
          'type' => 'string',
          'label' => $this->t('Source'),
          'description' => $this->t('Nursery, seed source, or propagation method for the batch.'),
          'weight' => ['form' => -65, 'view' => -65],
        ],
        'notes' => [
          'type' => 'text_long',
          'label' => $this->t('Notes'),
          'description' => $this->t('Free-form notes about the planting event.'),
          'weight' => ['form' => -10, 'view' => -10],
        ],
        // Open-ended WKT per spec decision — no geometry-type validator.
        'geometry' => [
          'type' => 'geofield',
          'label' => $this->t('Geometry'),
          'description' => $this->t('Row (LineString) or block (Polygon) geometry for this planting.'),
          'weight' => ['form' => 0, 'view' => 0],
        ],
      ];

      foreach ($field_info as $name => $info) {
        $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
      }

      return $fields;
    }

  }
  ```

- [ ] Syntax-check: `php -l modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php`

### Task 5 — Run Task 3 tests — expect PASS

- [ ] Run all Task 3 tests:
  ```
  docker compose -f docker/docker-compose.farm-syntropic.yml exec www \
    php /opt/drupal/vendor/bin/phpunit \
    --filter "testTreePlantingBundleRegistered|testTreePlantingFieldsRegistered|testTreeParentPlantingFieldRegistered|testTreePlantingTreeCountValidation" \
    web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```
  Expected: 3 passed, 1 failed (`testTreeParentPlantingFieldRegistered` still FAILs — `parent_planting` not yet on Tree).

### Task 6 — Add `parent_planting` field to `Tree::buildFieldDefinitions()`

- [ ] In `modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php`, add this entry to `$field_info` after `'odoo_lot'`:
  ```php
  // Parent planting: typed reference to a tree_planting asset.
  // FarmFieldFactory::modifyEntityReferenceField() case 'asset' (lines
  // 354-367 of FarmFieldFactory.php) honors 'target_bundle' for asset
  // targets the same way it does for taxonomy_term targets. No
  // config/install override is needed.
  'parent_planting' => [
    'type' => 'entity_reference',
    'label' => $this->t('Tree Planting'),
    'description' => $this->t('The grouped planting this tree belongs to.'),
    'target_type' => 'asset',
    'target_bundle' => 'tree_planting',
    'multiple' => FALSE,
    'weight' => ['form' => -80, 'view' => -80],
  ],
  ```

- [ ] Syntax-check: `php -l modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php`

### Task 7 — Run `testTreeParentPlantingFieldRegistered` — expect PASS

- [ ] Run:
  ```
  docker compose -f docker/docker-compose.farm-syntropic.yml exec www \
    php /opt/drupal/vendor/bin/phpunit \
    --filter testTreeParentPlantingFieldRegistered \
    web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```
  Expected: `OK (1 test, 3 assertions)`

### Task 8 — Update `testTreeFieldsRegistered` to include `parent_planting` (13 → 14)

- [ ] In the test file, add `'parent_planting'` to the `$expected` array in `testTreeFieldsRegistered()`:
  ```php
  $expected = [
    'species', 'variety', 'dbh_cm', 'height_m', 'canopy_radius_m',
    'stratum', 'succession_stage', 'health_status',
    'rootstock', 'graft_variety', 'planting_date', 'source', 'odoo_lot',
    'parent_planting',   // reverse navigation to tree_planting assets
  ];
  ```

- [ ] Update the docblock from `All 13 Tree custom fields exist` to `All 14 Tree custom fields exist`.

- [ ] Run: `--filter testTreeFieldsRegistered` — expect `OK (1 test, 1 assertion)`.

### Task 9 — Update `testAssetBundlesRegistered` to include `tree_planting` (2 → 3)

- [ ] Update the loop array in the test:
  ```php
  foreach (['tree', 'infrastructure', 'tree_planting'] as $bundle_id) {
  ```

- [ ] Update docblock to mention three bundles.

- [ ] Run the full kernel test class to confirm no regressions:
  ```
  docker compose -f docker/docker-compose.farm-syntropic.yml exec www \
    php /opt/drupal/vendor/bin/phpunit \
    web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```
  Expected: all tests green.

### Task 10 — Create the embedded Drupal View

- [ ] Create `modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml`:
  ```yaml
  langcode: en
  status: true
  dependencies:
    config:
      - asset.type.tree
      - asset.type.tree_planting
    enforced:
      module:
        - farm_syntropic
    module:
      - asset
      - views
  id: tree_planting_trees
  label: 'Trees in this Planting'
  module: views
  description: 'Lists individual Tree assets linked to a given Tree Planting via the parent_planting field.'
  tag: ''
  base_table: asset_field_data
  base_field: id
  display:
    default:
      id: default
      display_plugin: default
      display_title: Default
      position: 0
      display_options:
        title: 'Trees in this Planting'
        use_ajax: false
        use_pager: false
        items_per_page: 200
        fields:
          name:
            id: name
            table: asset_field_data
            field: name
            relationship: none
            label: Tree
            plugin_id: field
        filters:
          type:
            id: type
            table: asset_field_data
            field: type
            value:
              tree: tree
            plugin_id: bundle
          status:
            id: status
            table: asset_field_data
            field: status
            value: '1'
            plugin_id: boolean
        arguments:
          parent_planting_target_id:
            id: parent_planting_target_id
            table: asset__parent_planting
            field: parent_planting_target_id
            relationship: none
            default_action: empty
            exception:
              value: all
            default_argument_type: fixed
            default_argument_options:
              argument: ''
            plugin_id: numeric
        style:
          type: table
          options:
            row_class: ''
            default_row_class: true
            columns:
              name: name
            info:
              name:
                sortable: false
                default_sort_order: asc
                align: ''
                separator: ''
                empty_column: false
                responsive: ''
            default: '-1'
            empty_table: false
        row:
          type: fields
        empty:
          area_text_custom:
            id: area_text_custom
            table: views
            field: area_text_custom
            content: 'No individual trees are linked to this planting yet.'
            plugin_id: text_custom
        access:
          type: none
          options: {}
        cache:
          type: tag
          options: {}
    page_embed:
      id: page_embed
      display_plugin: embed
      display_title: 'Planting page embed'
      position: 1
      display_options:
        display_extenders: {}
        title: 'Trees in this Planting'
        defaults:
          title: false
  ```

  **Note**: the argument `parent_planting_target_id` on table `asset__parent_planting` uses Drupal's standard `{entity_type}__{field_name}` storage table convention; the column is `{field_name}_target_id`. Confirm after module install:
  ```
  drush php:eval "print_r(\Drupal::database()->schema()->tableExists('asset__parent_planting') ? 'exists' : 'missing');"
  ```

- [ ] Validate YAML:
  ```
  python3 -c "import yaml; yaml.safe_load(open('modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml'))" && echo "OK"
  ```

### Task 11 — Extend smoke test — bundle check and two new steps

In `.github/workflows/smoke-farm-syntropic.yml`:

- [ ] **Extend existing bundle check.** Change `$expected_bundles = ['tree', 'infrastructure'];` to `$expected_bundles = ['tree', 'infrastructure', 'tree_planting'];`.

- [ ] **Update existing Tree field assertion step.** Change the field-list comment from `all 13 Tree custom fields` to `all 14 Tree custom fields` and add `'parent_planting'` to `$expected_fields`:
  ```php
  $expected_fields = [
    'species', 'variety', 'dbh_cm', 'height_m', 'canopy_radius_m',
    'stratum', 'succession_stage', 'health_status',
    'rootstock', 'graft_variety', 'planting_date', 'source', 'odoo_lot',
    'parent_planting',
  ];
  ```
  Update the success print line to `"OK: all 14 Tree custom fields are registered\n"`.

- [ ] **Add new step "Create Tree Planting and link Tree".** Insert between the existing "Create a test Tree asset" step and "Verify JSON:API exposes the tree asset":
  ```yaml
      - name: Create Tree Planting and link Tree — verify bidirectionality
        env:
          ASSERT_SCRIPT: |
            $planting = \Drupal::entityTypeManager()->getStorage('asset')->create([
              'type' => 'tree_planting',
              'name' => 'CI smoke planting - Row A',
              'tree_count' => 5,
              'spacing_m' => '3.00',
            ]);
            $planting->save();
            $planting_id = $planting->id();
            print "OK: created tree_planting asset id=$planting_id\n";

            $tree = \Drupal::entityTypeManager()->getStorage('asset')->create([
              'type' => 'tree',
              'name' => 'CI smoke tree - linked to planting',
              'parent_planting' => [['target_id' => $planting_id]],
            ]);
            $tree->save();
            $tree_id = $tree->id();

            $reloaded_tree = \Drupal::entityTypeManager()->getStorage('asset')->load($tree_id);
            $resolved_id = (int) $reloaded_tree->get('parent_planting')->target_id;
            if ($resolved_id !== (int) $planting_id) {
              throw new \Exception(
                "FAIL: parent_planting target_id is $resolved_id, expected $planting_id"
              );
            }
            print "OK: Tree id=$tree_id correctly references tree_planting id=$planting_id\n";

            $reloaded_planting = \Drupal::entityTypeManager()->getStorage('asset')->load($planting_id);
            if ((int) $reloaded_planting->get('tree_count')->value !== 5) {
              throw new \Exception("FAIL: tree_count did not persist (got '" . $reloaded_planting->get('tree_count')->value . "')");
            }
            print "OK: tree_planting tree_count=5 persists correctly\n";

            // Bundle constraint: assigning a non-tree_planting to parent_planting must violate.
            $infra = \Drupal::entityTypeManager()->getStorage('asset')->create([
              'type' => 'infrastructure',
              'name' => 'CI infra probe',
            ]);
            $infra->save();
            $bad_tree = \Drupal::entityTypeManager()->getStorage('asset')->create([
              'type' => 'tree',
              'name' => 'CI bad tree',
              'parent_planting' => [['target_id' => $infra->id()]],
            ]);
            $violations = $bad_tree->validate();
            $field_violations = $violations->getByField('parent_planting');
            if ($field_violations->count() === 0) {
              throw new \Exception(
                "FAIL: assigning an infrastructure asset to parent_planting should produce a violation"
              );
            }
            print "OK: bundle constraint rejects non-tree_planting target on parent_planting\n";
        run: |
          docker compose -f docker-compose.smoke.yml exec -T -e ASSERT_SCRIPT="$ASSERT_SCRIPT" www \
            drush php:eval "$ASSERT_SCRIPT"
  ```

- [ ] **Add new step "Verify JSON:API exposes tree_planting".** Insert after the new step above:
  ```yaml
      - name: Verify JSON:API exposes tree_planting
        run: |
          docker compose -f docker-compose.smoke.yml exec -T www \
            curl -fsS -u admin:admin \
              -H 'Accept: application/vnd.api+json' \
              'http://localhost/jsonapi/asset/tree_planting' \
            | python3 -c "
          import json, sys
          data = json.load(sys.stdin)
          if 'data' not in data:
              sys.exit('FAIL: JSON:API response missing data field')
          if len(data['data']) < 1:
              sys.exit('FAIL: expected at least 1 tree_planting asset in JSON:API response')
          planting = data['data'][0]
          for f in ['tree_count', 'spacing_m']:
              if f not in planting['attributes']:
                  sys.exit(f'FAIL: JSON:API tree_planting asset missing attribute \"{f}\"')
          if planting['attributes']['tree_count'] != 5:
              sys.exit('FAIL: tree_count should be 5, got ' + str(planting['attributes']['tree_count']))
          print('OK: JSON:API exposes tree_planting asset with tree_count and spacing_m attributes')
          "
  ```

### Task 12 — Update `docs/workflows/tree-inventory-data-entry.md`

- [ ] Insert a new section before `## For Each Tree` (preceded by a `---` divider):
  ````markdown
  ---

  ## When to use Tree Planting first (recommended for new rows or blocks)

  Use this path when you are recording a batch of same-species trees planted together — a row, a block, or any group where the trees share species, variety, spacing, and planting date. A single Tree Planting asset is the inventory record for the whole batch.

  ### Step 1: Navigate to Add Tree Planting

  Go to `/asset/add/tree_planting` or tap **+ Add Asset** and choose **Tree Planting**.

  ### Step 2: Name the planting

  Use a name that identifies the row or block:

  - `Row A - American Chestnut`
  - `Orchard North - Block 2 - Dunstan Chestnut`

  ### Step 3: Fill in batch fields

  | Field | What to enter | Example |
  |-------|--------------|---------|
  | **Species** | Pick from autocomplete (same vocabulary as Tree species) | `American Chestnut` |
  | **Variety** | Cultivar name for the batch | `Dunstan` |
  | **Tree count** | Total trees installed in this batch | `12` |
  | **Spacing (m)** | On-center spacing in meters (rows only; leave blank for blocks) | `3.00` |
  | **Planting date** | Date the batch went in | `2025-03-15` |
  | **Source** | Nursery or seed source | `At the Grove Nursery` |
  | **Notes** | Any planting-event notes | `Planted on contour, healthy at install` |

  > **Tip:** Tree count is a declared value — it reflects how many trees you planted, not how many are alive today. Update it manually if trees die or are removed.

  ### Step 4: Draw the geometry

  1. Click the **Location** tab on the left sidebar.
  2. Toggle **Is fixed** to ON.
  3. For a **row**: use the line tool (polyline icon) and tap two or more endpoints along the row.
  4. For a **block**: use the polygon tool and trace the block boundary.

  Geometry is open-ended — a row is a `LineString`, a block is a `Polygon`. You can also paste WKT directly into the text area.

  ### Step 5: Save

  Tap **Save**. The Tree Planting is your inventory record for the batch.

  ### Step 6: Link individual trees (optional)

  Create individual Tree assets only for trees that need individual tracking (a dead tree, a grafted variant, a sensor-mounted specimen). On each Tree asset, set the **Tree Planting** field to point back to this planting. The planting page will then show a "Trees in this Planting" list of all linked trees.

  You do not need to create individual Tree assets for every tree in a batch — the planting itself is the record.

  ---
  ````

- [ ] Update the `### Group of trees (same species in a row)` sub-section under `## Handling Special Cases`. Replace its content with:
  ```markdown
  ### Group of trees (same species in a row or block)

  Use the **Tree Planting** path described in "When to use Tree Planting first" above. A single Tree Planting asset records the species, count, spacing, and geometry for the entire batch.

  If you have already entered individual trees without a parent planting, you can retroactively link them: open each Tree asset and set the **Tree Planting** field. The data model supports this; no migration is required.
  ```

### Task 13 — Update `CHANGELOG.md`

- [ ] Under `## [Unreleased]` → `### Added`:
  ```markdown
  - AgriforestryOS fork: `tree_planting` asset bundle — records a row or block of same-species trees with species, variety, tree count, spacing, planting date, source, notes, and geometry fields. Shipped via `TreePlanting.php` plugin and `asset.type.tree_planting.yml` config entity.
  - AgriforestryOS fork: `parent_planting` entity-reference field on the Tree asset type — typed to accept only `tree_planting` assets (via `FarmFieldFactory` `target_bundle` shorthand for `target_type: asset`).
  - AgriforestryOS fork: Drupal View `tree_planting_trees` — embedded on the Tree Planting asset page, lists all Tree assets linked via `parent_planting`. Shipped in `config/install/views.view.tree_planting_trees.yml`.
  - AgriforestryOS fork: kernel test methods `testTreePlantingBundleRegistered`, `testTreePlantingFieldsRegistered`, `testTreeParentPlantingFieldRegistered`, and `testTreePlantingTreeCountValidation` in `FarmSyntropicFieldSchemaTest`.
  ```

- [ ] Under `### Changed`:
  ```markdown
  - AgriforestryOS fork: `docs/workflows/tree-inventory-data-entry.md` — added "When to use Tree Planting first" section as the recommended path for rows and blocks; demoted the per-tree workflow to a secondary path.
  - AgriforestryOS fork: smoke test workflow extended — `tree_planting` added to bundle verification; new steps validate planting creation, Tree-to-Planting bidirectional link, bundle constraint enforcement, and JSON:API exposure.
  - AgriforestryOS fork: `testTreeFieldsRegistered` updated — `parent_planting` added to expected field list (count 13 → 14); `testAssetBundlesRegistered` updated to include `tree_planting` (count 2 → 3).
  ```

### Task 14 — Local static analysis and linting

- [ ] **actionlint**:
  ```
  docker run --rm -v "${PWD}:/repo" -w /repo rhysd/actionlint:1.7.7 -color \
    .github/workflows/smoke-farm-syntropic.yml
  ```
  Expected: no errors.

- [ ] **php -l** on all touched PHP:
  ```
  php -l modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php
  php -l modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php
  php -l modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```

- [ ] **phpstan** at level 5:
  ```
  docker run --rm \
    -v "${PWD}/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
    -w /opt/drupal/web/profiles/farm \
    farmos/farmos:4.x-dev \
    phpstan analyze --level 5 --memory-limit 1G --no-progress /opt/drupal/web/modules/farm_syntropic
  ```
  Expected: `[OK] No errors`.

- [ ] **yamllint**:
  ```
  python3 -c "
  import yaml, pathlib
  for f in [
    'modules/farm_syntropic/config/install/asset.type.tree_planting.yml',
    'modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml',
    '.github/workflows/smoke-farm-syntropic.yml',
  ]:
    yaml.safe_load(pathlib.Path(f).read_text())
    print('OK:', f)
  "
  ```

### Task 15 — Full kernel test suite — final green gate

- [ ] Run the complete kernel class one more time:
  ```
  docker compose -f docker/docker-compose.farm-syntropic.yml exec www \
    php /opt/drupal/vendor/bin/phpunit \
    --testdox \
    web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php
  ```
  Expected: all tests green, count includes the 4 new methods.

### Task 16 — Open PR and verify CI

- [ ] Commit all changes:
  ```
  git checkout -b feature/tree-planting-asset-type
  git add \
    modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php \
    modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php \
    modules/farm_syntropic/config/install/asset.type.tree_planting.yml \
    modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml \
    modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
    .github/workflows/smoke-farm-syntropic.yml \
    docs/workflows/tree-inventory-data-entry.md \
    CHANGELOG.md
  git commit -m "feat(farm_syntropic): add tree_planting asset bundle and parent_planting field on Tree"
  git push -u origin feature/tree-planting-asset-type
  ```

- [ ] Open PR against `4.x`. Wait for required checks:
  - `farm_syntropic CI (fast)` — PHP lint + PHPStan
  - `farm_syntropic PHPStan` — level 5
  - `farm_syntropic smoke test` — extended with 3 new assertions

- [ ] Once all green, merge.

---

## Self-Review

**Open Question resolution quality:** OQ #1 cites the exact `FarmFieldFactory.php` lines 354-367 that prove `target_bundle` works for asset targets. OQ #2 commits to the Drupal View approach (declarative, overridable). OQ #3 confirms `drush en` handles config import without extra rebuild.

**No data migration needed:** `parent_planting` is optional with no default. Existing Tree assets have `NULL` — Drupal handles this transparently for both storage and JSON:API.

**Geometry field:** `type: geofield`, no custom validator. Open-ended WKT per spec.

**Test isolation:** All new tests use `Asset::create()` with type-specific data; no shared state. `#[RunTestsInSeparateProcesses]` is already on the class.

**View YAML robustness note:** If the Views UI reports a broken handler after install, run `drush views:analyze`. Most common cause is the `asset__parent_planting` storage table not existing yet — resolved by `drush entity:updates`. The Table existence probe (Task 10) catches this before CI.

**Files Touched summary** (one-line per file appears in the File Structure section above).
