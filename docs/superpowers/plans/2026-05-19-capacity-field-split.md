# Capacity Field Split Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the single free-text `capacity` field on the Infrastructure asset type with a structured `capacity_value` (decimal) and `capacity_unit` (list_string) pair that is queryable via JSON:API and Drupal Views.

**Architecture:** Both new fields follow the existing Tree decimal+list_string pattern in `Infrastructure.php`, added to `$field_info` via `farmFieldFactory->bundleFieldDefinition()`. A new `farm_syntropic.install` provides `farm_syntropic_update_8001()` which logs any existing capacity strings at WARNING level, drops the old field via `entityDefinitionUpdateManager->uninstallFieldStorageDefinition()` (the correct API for bundle fields — not `FieldStorageConfig::loadByName()`), then calls `applyUpdates()` so Drupal installs the two new columns automatically. The kernel test gains one updated field-list assertion and one new range-validation test mirroring the existing Tree decimal tests.

**Tech Stack:** Drupal 11, PHP 8.4, farmOS 4.x, FarmFieldFactory

---

## File Structure

| Action | File | Purpose |
|---|---|---|
| Modify | `modules/farm_syntropic/src/Plugin/Asset/AssetType/Infrastructure.php` | Replace `capacity` string field with `capacity_value` decimal + `capacity_unit` list_string; remove `TODO(phase-2)` block |
| Create | `modules/farm_syntropic/farm_syntropic.install` | `@file` docblock + `farm_syntropic_update_8001()` migration hook |
| Modify | `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php` | Update `testInfrastructureFieldsRegistered` (6→7 fields, swap field names); add `testCapacityValueRangeValidation` |
| Modify | `.github/workflows/smoke-farm-syntropic.yml` | Add Infrastructure capacity field shape assertion step |
| Modify | `CHANGELOG.md` | Added + Changed entries; JSON:API breaking change noted |

---

## Tasks

### Task 1: Create `farm_syntropic.install` skeleton

- [ ] Create `/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/farm_syntropic.install` with the following content — skeleton only, no update hook body yet:

```php
<?php

/**
 * @file
 * Install, update and uninstall functions for the farm_syntropic module.
 */

declare(strict_types=1);
```

- [ ] Verify PHP syntax passes locally:

```bash
php -l "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/farm_syntropic.install"
```

Expected output: `No syntax errors detected in .../farm_syntropic.install`

---

### Task 2: Write a failing kernel test asserting `capacity_value` and `capacity_unit` exist

This is the red phase for the range-validation test. Add the method before any implementation changes so you can confirm it fails.

- [ ] Open `/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php` and insert the following method after `testInfrastructureFieldsRegistered()` (after line 154, before `testTaxonomyVocabulariesRegistered()`):

```php
  /**
   * Negative values on capacity_value are rejected; zero and positive pass.
   *
   * Mirrors testDecimalRangeValidation() for the Tree asset type.
   * FarmFieldFactory enforces the min:0 setting via entity validation, which
   * gates both the form widget and JSON:API writes.
   */
  public function testCapacityValueRangeValidation(): void {
    $infra = Asset::create([
      'type' => 'infrastructure',
      'name' => 'Capacity range probe',
      'infrastructure_type' => 'solar',
      'capacity_value' => '-1',
    ]);
    $violations = $infra->validate();
    $this->assertGreaterThan(
      0,
      $violations->getByField('capacity_value')->count(),
      'Expected a validation violation for capacity_value = -1',
    );

    // Zero is on the boundary — must be accepted.
    $infra->set('capacity_value', '0');
    $violations = $infra->validate();
    $this->assertSame(
      0,
      $violations->getByField('capacity_value')->count(),
      'capacity_value = 0 should not produce a violation',
    );

    // Positive value must be accepted.
    $infra->set('capacity_value', '400.000');
    $violations = $infra->validate();
    $this->assertSame(
      0,
      $violations->getByField('capacity_value')->count(),
      'capacity_value = 400.000 should not produce a violation',
    );
  }
```

- [ ] Run the new test to confirm it fails (the field does not exist yet):

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  php /opt/drupal/vendor/bin/phpunit \
    --configuration /opt/drupal/web/profiles/farm/phpunit.xml \
    /opt/drupal/web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
    --filter testCapacityValueRangeValidation \
    --no-coverage 2>&1 | tail -20
```

Expected: 1 test, 1 failure (field `capacity_value` does not exist).

---

### Task 3: Update `Infrastructure.php` — replace `capacity` with `capacity_value` + `capacity_unit`

- [ ] Open `/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/src/Plugin/Asset/AssetType/Infrastructure.php`.

- [ ] Delete lines 49–59 in their entirety (the `// TODO(phase-2)` block and the `'capacity'` entry).

- [ ] In the vacated position (between `'material'` and `'installation_date'`), insert:

```php
      'capacity_value' => [
        'type' => 'decimal',
        'label' => $this->t('Capacity value'),
        'description' => $this->t('Numeric magnitude (e.g. 400 for a 400 W panel).'),
        'precision' => 10,
        'scale' => 3,
        'min' => 0,
        'weight' => [
          'form' => -47,
          'view' => -47,
        ],
      ],
      'capacity_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Capacity unit'),
        'description' => $this->t('Unit of measure for the capacity value.'),
        'allowed_values' => [
          'watts' => 'Watts (W)',
          'kilowatts' => 'Kilowatts (kW)',
          'gpm' => 'Gallons per minute (GPM)',
          'lpm' => 'Litres per minute (LPM)',
          'gallons' => 'Gallons (gal)',
          'litres' => 'Litres (L)',
          'amps' => 'Amps (A)',
          'volts' => 'Volts (V)',
          'linear_feet' => 'Linear feet (ft)',
          'linear_meters' => 'Linear meters (m)',
        ],
        'weight' => [
          'form' => -46,
          'view' => -46,
        ],
      ],
```

- [ ] Verify PHP syntax:

```bash
php -l "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/src/Plugin/Asset/AssetType/Infrastructure.php"
```

Expected: `No syntax errors detected`

---

### Task 4: Run `testCapacityValueRangeValidation` — must now pass

- [ ] Run the test:

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  php /opt/drupal/vendor/bin/phpunit \
    --configuration /opt/drupal/web/profiles/farm/phpunit.xml \
    /opt/drupal/web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
    --filter testCapacityValueRangeValidation \
    --no-coverage 2>&1 | tail -20
```

Expected: 1 test, 0 failures. If it still fails, confirm the Docker image picked up the updated `Infrastructure.php` from the bind-mount.

---

### Task 5: Update `testInfrastructureFieldsRegistered` — swap field names, update docblock count

- [ ] In `FarmSyntropicFieldSchemaTest.php`, replace the `testInfrastructureFieldsRegistered()` method (lines 139–154) with:

```php
  /**
   * All 7 Infrastructure custom fields exist.
   *
   * If you add or rename a field on the Infrastructure asset type plugin,
   * update this list. The intent of the test is to make field-name changes
   * visible (they're a JSON:API breaking change).
   */
  public function testInfrastructureFieldsRegistered(): void {
    $expected = [
      'infrastructure_type',
      'material',
      'capacity_value',
      'capacity_unit',
      'installation_date',
      'condition',
      'specifications',
    ];
    $actual = array_keys($this->fieldManager->getFieldDefinitions('asset', 'infrastructure'));
    $missing = array_diff($expected, $actual);
    $this->assertEmpty(
      $missing,
      'Missing Infrastructure fields: ' . implode(', ', $missing),
    );
  }
```

- [ ] Run the full kernel test suite to confirm all tests pass:

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  php /opt/drupal/vendor/bin/phpunit \
    --configuration /opt/drupal/web/profiles/farm/phpunit.xml \
    /opt/drupal/web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
    --no-coverage 2>&1 | tail -20
```

Expected: 7 tests, 0 failures, 0 errors.

---

### Task 6: Implement `farm_syntropic_update_8001()` in `farm_syntropic.install`

**API decision (resolves spec Open Question #2):** Bundle fields created via `farmFieldFactory->bundleFieldDefinition()` are registered in Drupal's last-installed schema repository through the bundle plugin system (`FarmEntityBundlePluginHandler::getFieldStorageDefinitions()`). They are **not** stored as `FieldStorageConfig` configuration entities. Therefore `FieldStorageConfig::loadByName('asset', 'capacity')` returns `NULL` for this field — using it would produce a fatal error on `->delete()`. The correct deletion path is:

1. `$update_manager->getFieldStorageDefinition('capacity', 'asset')` — reads from `getLastInstalledFieldStorageDefinitions()`, which includes bundle fields (`EntityDefinitionUpdateManager.php:222-224`).
2. `$update_manager->uninstallFieldStorageDefinition($definition)` — fires `onFieldStorageDefinitionDelete`, drops the column, purges data (`EntityDefinitionUpdateManager.php:239-242`). Same pattern as `simple_oauth.install:400-405`.

After deletion, `applyUpdates()` detects `capacity_value` and `capacity_unit` as new definitions from `Infrastructure::buildFieldDefinitions()` and creates their columns automatically. No `installFieldStorageDefinition` call is needed.

- [ ] Replace the skeleton in `farm_syntropic.install` with the full file:

```php
<?php

/**
 * @file
 * Install, update and uninstall functions for the farm_syntropic module.
 */

declare(strict_types=1);

/**
 * Split Infrastructure capacity field into capacity_value + capacity_unit.
 *
 * The old 'capacity' field was a free-text string (e.g. "400W", "15 GPM").
 * The new fields are a decimal 'capacity_value' and a list_string
 * 'capacity_unit', which are queryable in JSON:API and Drupal Views.
 *
 * JSON:API BREAKING CHANGE: the 'capacity' attribute is removed from the
 * /jsonapi/asset/infrastructure endpoint. Clients must migrate to
 * 'capacity_value' and 'capacity_unit'.
 *
 * Existing capacity string values are NOT automatically parsed — real-world
 * operator input ("400w (2 panels)", "~400W") defeats any regex and silent
 * misclassification is worse than NULL. Existing values are logged at WARNING
 * level so operators can re-enter them.
 */
function farm_syntropic_update_8001(): string {
  $update_manager = \Drupal::entityDefinitionUpdateManager();
  $entity_storage = \Drupal::entityTypeManager()->getStorage('asset');
  $logger = \Drupal::logger('farm_syntropic');

  // Step 1: Query existing Infrastructure assets and capture capacity values.
  $ids = $entity_storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('type', 'infrastructure')
    ->execute();

  $logged_count = 0;
  if (!empty($ids)) {
    $assets = $entity_storage->loadMultiple($ids);
    $values_to_log = [];
    foreach ($assets as $asset) {
      // Use hasField() guard because the field is being dropped in this hook;
      // on a fresh install this field may not exist.
      if ($asset->hasField('capacity')) {
        $capacity = $asset->get('capacity')->value ?? NULL;
        if ($capacity !== NULL && $capacity !== '') {
          $values_to_log[] = sprintf(
            'asset %d (%s): capacity="%s"',
            $asset->id(),
            $asset->label(),
            $capacity,
          );
        }
      }
    }
    if (!empty($values_to_log)) {
      $logged_count = count($values_to_log);
      $logger->warning(
        'farm_syntropic_update_8001: @count Infrastructure asset(s) had a ' .
        'capacity value that cannot be automatically migrated. ' .
        'Re-enter capacity as value+unit on the following assets: @list',
        [
          '@count' => $logged_count,
          '@list' => implode('; ', $values_to_log),
        ],
      );
    }
  }

  // Step 2: Drop the old 'capacity' bundle field storage definition.
  //
  // IMPORTANT: Do NOT use FieldStorageConfig::loadByName('asset', 'capacity').
  // That method only works for configurable fields stored as config entities.
  // Fields created via FarmFieldFactory::bundleFieldDefinition() are registered
  // in Drupal's last-installed schema repository through the bundle plugin
  // system (FarmEntityBundlePluginHandler), not as config entities.
  // getFieldStorageDefinition() reads from getLastInstalledFieldStorageDefinitions(),
  // which includes bundle fields. uninstallFieldStorageDefinition() then fires
  // onFieldStorageDefinitionDelete, dropping the column and purging data.
  $old_definition = $update_manager->getFieldStorageDefinition('capacity', 'asset');
  if ($old_definition) {
    $update_manager->uninstallFieldStorageDefinition($old_definition);
  }

  // Step 3: Apply updates so Drupal installs the two new fields declared in
  // Infrastructure::buildFieldDefinitions(). The field manager cache was
  // cleared inside uninstallFieldStorageDefinition(); applyUpdates() detects
  // capacity_value and capacity_unit as new definitions and creates their
  // database columns.
  $update_manager->applyUpdates();

  if ($logged_count > 0) {
    return sprintf(
      'Dropped old capacity field. %d asset(s) had existing capacity values ' .
      'that could not be auto-migrated — check the watchdog log for details ' .
      'and re-enter them via the UI.',
      $logged_count,
    );
  }

  return 'Dropped old capacity field (no existing data found). ' .
    'New capacity_value and capacity_unit fields are now active.';
}
```

- [ ] Verify PHP syntax:

```bash
php -l "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/farm_syntropic.install"
```

Expected: `No syntax errors detected`

---

### Task 7: Run PHPStan against the full module

- [ ] Run PHPStan using the exact same Docker invocation as the CI workflow (from `.github/workflows/phpstan-farm-syntropic.yml`):

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  phpstan analyze \
    --level 5 \
    --memory-limit 1G \
    --no-progress \
    /opt/drupal/web/modules/farm_syntropic
```

Expected: `[OK] No errors`

If PHPStan flags `$asset->get('capacity')->value` as accessing a field on a possibly-null return (level 5 can flag this), the `$asset->hasField('capacity')` guard in the hook already addresses the logical concern; ensure the static-analysis type path is also satisfied. If needed, add an explicit null check: `$item = $asset->hasField('capacity') ? $asset->get('capacity')->first() : NULL; $capacity = $item?->value ?? NULL;` and re-run.

---

### Task 8: Add Infrastructure capacity field shape assertion to the smoke test

- [ ] Open `/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/.github/workflows/smoke-farm-syntropic.yml`.

- [ ] Insert the following step after the "Verify Tree custom fields are registered" step (after line 172) and before the "Create a test Tree asset and read it back" step:

```yaml
      - name: Verify Infrastructure capacity fields are registered
        env:
          ASSERT_SCRIPT: |
            $expected_fields = [
              'infrastructure_type', 'material',
              'capacity_value', 'capacity_unit',
              'installation_date', 'condition', 'specifications',
            ];
            $fields = \Drupal::service('entity_field.manager')
              ->getFieldDefinitions('asset', 'infrastructure');
            $missing = array_diff($expected_fields, array_keys($fields));
            if (!empty($missing)) {
              throw new \Exception("FAIL: missing Infrastructure fields: " . implode(', ', $missing));
            }
            if (array_key_exists('capacity', $fields)) {
              throw new \Exception("FAIL: old 'capacity' field still registered — should have been removed");
            }
            if ($fields['capacity_value']->getType() !== 'decimal') {
              throw new \Exception("FAIL: capacity_value type should be 'decimal', got '" . $fields['capacity_value']->getType() . "'");
            }
            if ($fields['capacity_unit']->getType() !== 'list_string') {
              throw new \Exception("FAIL: capacity_unit type should be 'list_string', got '" . $fields['capacity_unit']->getType() . "'");
            }
            $allowed = $fields['capacity_unit']->getSetting('allowed_values');
            if (!array_key_exists('watts', $allowed)) {
              throw new \Exception("FAIL: capacity_unit allowed_values missing 'watts' key");
            }
            print "OK: all 7 Infrastructure custom fields registered with correct types\n";
        run: |
          docker compose -f docker-compose.smoke.yml exec -T -e ASSERT_SCRIPT="$ASSERT_SCRIPT" www \
            drush php:eval "$ASSERT_SCRIPT"
```

- [ ] Verify the workflow file passes YAML lint:

```bash
pip install --user yamllint==1.35.1 2>/dev/null || true
~/.local/bin/yamllint \
  -d "{extends: relaxed, rules: {line-length: disable, document-start: disable, truthy: disable}}" \
  "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/.github/workflows/smoke-farm-syntropic.yml"
```

Expected: no output (clean).

---

### Task 9: Update `CHANGELOG.md`

- [ ] Open `/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/CHANGELOG.md`.

- [ ] Append to the existing `### Added` block under `## [Unreleased]`:

```markdown
- AgriforestryOS fork: Infrastructure asset type gains two structured capacity fields: `capacity_value` (decimal, precision 10 scale 3, min 0) and `capacity_unit` (list_string with 10 controlled units covering solar, irrigation, electrical, and fencing). Values are queryable via JSON:API and Drupal Views.
- AgriforestryOS fork: `farm_syntropic.install` with `farm_syntropic_update_8001()` — logs any existing Infrastructure `capacity` strings at WARNING level before dropping the old field, then applies schema updates to install the two new fields.
```

- [ ] Append to the existing `### Changed` block under `## [Unreleased]`:

```markdown
- AgriforestryOS fork: **JSON:API breaking change** — the `capacity` attribute is removed from the `/jsonapi/asset/infrastructure` endpoint and replaced by `capacity_value` and `capacity_unit`. The field carried a `TODO(phase-2)` comment since its initial commit and has no confirmed external consumers. Run `drush updb` after deploying to apply `farm_syntropic_update_8001()`.
```

---

### Task 10: Run the full local pre-push CI suite

All four gates must be clean before pushing.

- [ ] **PHP syntax** — all PHP files in the module:

```bash
find "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic" \
  -type f \( -name '*.php' -o -name '*.module' -o -name '*.install' \) -print0 \
  | xargs -0 -n1 php -l
```

Expected: every line reads `No syntax errors detected`.

- [ ] **YAML lint** — module config and workflow files:

```bash
~/.local/bin/yamllint \
  -d "{extends: relaxed, rules: {line-length: disable, document-start: disable, truthy: disable}}" \
  "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic/"
```

Expected: no output.

- [ ] **actionlint** — owned workflow files:

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS:/repo" \
  -w /repo \
  rhysd/actionlint:1.7.7 -color \
    .github/workflows/ci-farm-syntropic.yml \
    .github/workflows/smoke-farm-syntropic.yml \
    .github/workflows/phpstan-farm-syntropic.yml \
    .github/workflows/enforce-changelog.yml
```

Expected: no errors.

- [ ] **PHPStan level 5**:

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  phpstan analyze \
    --level 5 \
    --memory-limit 1G \
    --no-progress \
    /opt/drupal/web/modules/farm_syntropic
```

Expected: `[OK] No errors`

- [ ] **Full kernel test suite** — all 7 methods must pass:

```bash
docker run --rm \
  -v "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS/modules/farm_syntropic:/opt/drupal/web/modules/farm_syntropic:ro" \
  -w /opt/drupal/web/profiles/farm \
  farmos/farmos:4.x-dev \
  php /opt/drupal/vendor/bin/phpunit \
    --configuration /opt/drupal/web/profiles/farm/phpunit.xml \
    /opt/drupal/web/modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
    --no-coverage 2>&1 | tail -20
```

Expected: 7 tests, 0 failures, 0 errors.

---

### Task 11: Open PR and wait for required CI checks

- [ ] Create a feature branch and commit all changes:

```bash
cd "/Users/joshuadunbar/Documents/Dev Projects/agriforestryOS"
git checkout -b feature/capacity-field-split
git add \
  modules/farm_syntropic/src/Plugin/Asset/AssetType/Infrastructure.php \
  modules/farm_syntropic/farm_syntropic.install \
  modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php \
  .github/workflows/smoke-farm-syntropic.yml \
  CHANGELOG.md
git commit -m "feat(farm_syntropic): split capacity into capacity_value + capacity_unit

Replaces the free-text 'capacity' string field on the Infrastructure
asset type with two queryable fields:
- capacity_value: decimal (precision 10, scale 3, min 0)
- capacity_unit: list_string (10 controlled units)

Adds farm_syntropic.install with farm_syntropic_update_8001() which logs
any existing capacity strings at WARNING level before dropping the old
field via entityDefinitionUpdateManager->uninstallFieldStorageDefinition().

JSON:API BREAKING CHANGE: 'capacity' attribute removed from
/jsonapi/asset/infrastructure. Replace with 'capacity_value' and
'capacity_unit'. Run 'drush updb' after deploying.

Updates kernel test: testInfrastructureFieldsRegistered (6->7 fields),
adds testCapacityValueRangeValidation. Adds smoke-test assertion for
field type and allowed_values shape."
git push origin feature/capacity-field-split
```

- [ ] Open a PR against `4.x`. PR description must include:
  - Link to `docs/superpowers/specs/2026-05-19-capacity-field-split-design.md`
  - Before/after JSON:API examples from the spec (`{"capacity": "400W"}` → `{"capacity_value": "400.000", "capacity_unit": "watts"}`)
  - Statement that no confirmed external consumers of the Infrastructure endpoint existed
  - `drush updb` instruction for any deployed instance

- [ ] Wait for all three required CI workflows to pass before merging:
  - `farm_syntropic CI (fast)` — actionlint, YAML lint, PHP syntax, gitleaks (runs on every PR)
  - `farm_syntropic PHPStan` — level 5 static analysis (triggered by `.php` and `.install` path filter)
  - `farm_syntropic smoke test` — full stack install + new Infrastructure field shape assertions (triggered by `modules/farm_syntropic/**` path filter)

- [ ] Squash-merge after all checks pass to keep `4.x` history clean.

---

## Self-Review

**Spec coverage check:**

| Spec requirement | Task |
|---|---|
| `capacity_value`: decimal, precision 10, scale 3, min 0, no max, weight form/view -47 | 3 |
| `capacity_unit`: list_string, 10 allowed values, weight form/view -46 | 3 |
| No paired-entry constraint | Omission — no custom validator added, partial entry allowed by default |
| `farm_syntropic.install` new file with `@file` docblock | 1, 6 |
| `farm_syntropic_update_8001()`: log existing `capacity` values at WARNING | 6 |
| `farm_syntropic_update_8001()`: drop old field via correct API | 6 |
| `farm_syntropic_update_8001()`: return summary string for `drush updb` | 6 |
| No auto-parsing of free-text values | 6 (explicit comment; logging only) |
| `testInfrastructureFieldsRegistered`: drop `capacity`, add two new fields, "7 fields" | 5 |
| `testCapacityValueRangeValidation`: negative rejected, 0 and positive pass | 2, 4 |
| Smoke test: Infrastructure field shape assertion (type + allowed_values) | 8 |
| `CHANGELOG.md`: Added + Changed + JSON:API breaking change | 9 |
| No upstream files touched | All tasks scoped to `modules/farm_syntropic/`, `CHANGELOG.md`, `.github/workflows/` |

**Open Question #2 resolution — definitive answer:**

`FieldStorageConfig::loadByName('asset', 'capacity')` — do NOT use. Returns `NULL` for bundle fields. The correct deletion sequence in `farm_syntropic_update_8001()` is:

```php
$definition = \Drupal::entityDefinitionUpdateManager()->getFieldStorageDefinition('capacity', 'asset');
if ($definition) {
    \Drupal::entityDefinitionUpdateManager()->uninstallFieldStorageDefinition($definition);
}
```

Evidence: `EntityDefinitionUpdateManager::getFieldStorageDefinition()` at line 222 of `docker/www/web/core/lib/Drupal/Core/Entity/EntityDefinitionUpdateManager.php` reads from `getLastInstalledFieldStorageDefinitions()`, which includes bundle fields registered by `FarmEntityBundlePluginHandler`. The `uninstallFieldStorageDefinition()` at line 239 fires `onFieldStorageDefinitionDelete`, dropping the column. The same pattern appears in `docker/www/web/modules/simple_oauth/simple_oauth.install` lines 400-405.

**Placeholder scan:** No `TODO`, `FIXME`, `<placeholder>`, or `...` placeholders appear in any code block. The commit message contains `#<issue>` as a literal instruction to the implementer — replace it with the actual issue number.

**Type consistency:** `capacity_value` uses `DECIMAL(10,3)` — wider than Tree's `DECIMAL(6,2)` fields to accommodate large infrastructure values (e.g., a 15,000-gallon cistern). This matches the spec precisely. `capacity_unit` `allowed_values` keys use underscores and lowercase (e.g., `linear_feet`) per Drupal convention for machine names; labels use natural capitalization (e.g., `Linear feet (ft)`).
