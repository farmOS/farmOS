# Capacity Field Split — Design

**Date:** 2026-05-19
**Status:** Approved (defaults applied), ready for `writing-plans`
**Scope:** `modules/farm_syntropic/` only — no upstream contribution

## Problem

The Infrastructure asset type's `capacity` field stores magnitude and unit as a single free-text string (`"400W"`, `"15 GPM"`, `"200 gal"`). This makes filtering, unit-aware comparison, and analytics impossible. The field's own description literally admits the unit ambiguity, and a `TODO(phase-2)` comment in the source documents this as deferred work.

## Goals

- Replace the free-text field with a structured **value + unit** pair that is queryable in JSON:API and Drupal Views.
- Use FarmFieldFactory primitives (no custom FieldType plugin).
- Provide a safe `hook_update_N()` path for the (likely empty) existing data.
- Honor the no-upstream policy: everything in `modules/farm_syntropic/`.
- Update the kernel test to reflect the new field schema.

## Non-Goals

- Parsing existing free-text values automatically (risky, low ROI, no confirmed production data).
- Supporting arbitrary user-defined units (controlled list is sufficient).
- Building a custom compound field widget (two side-by-side widgets are fine).
- Upstream contribution.

## Design

### Field Schema

Replace the single `capacity` field in `Infrastructure.php` with two independent fields:

**`capacity_value`**
- Type: `decimal`
- Precision: 10, scale: 3
- Label: `Capacity value`
- Description: `Numeric magnitude (e.g. 400 for a 400 W panel).`
- `min: 0`, no `max` (upper bound varies too widely across infrastructure types)
- Weight: `form: -47`, `view: -47`

**`capacity_unit`**
- Type: `list_string`
- Label: `Capacity unit`
- Description: `Unit of measure for the capacity value.`
- `allowed_values`:
  - `watts` → Watts (W)
  - `kilowatts` → Kilowatts (kW)
  - `gpm` → Gallons per minute (GPM)
  - `lpm` → Litres per minute (LPM)
  - `gallons` → Gallons (gal)
  - `litres` → Litres (L)
  - `amps` → Amps (A)
  - `volts` → Volts (V)
  - `linear_feet` → Linear feet (ft)
  - `linear_meters` → Linear meters (m)
- Weight: `form: -46`, `view: -46`

Both fields are optional. **No paired-entry constraint** — partial entry is acceptable. (See "Decisions Made" below.)

### Migration: `farm_syntropic_update_8001()`

Drupal cannot change field types in place; the only supported path is delete-then-create. The hook lives in a new file `modules/farm_syntropic/farm_syntropic.install`.

Steps:

1. Query all existing Infrastructure assets and capture the current `capacity` string value into a logged array: `[asset_id => name => old_capacity]`. Log at WARNING level via `\Drupal::logger('farm_syntropic')` with explicit instruction text: "Reenter capacity as value+unit on assets X, Y, Z."
2. Call `FieldStorageConfig::loadByName('asset', 'capacity')->delete()` to remove the old field and its data column.
3. Trigger a field definition rebuild via `\Drupal::service('entity_field.manager')->clearCachedFieldDefinitions()` then `\Drupal::entityDefinitionUpdateManager()->applyUpdates()`.
4. The two new fields are then picked up from `Infrastructure::buildFieldDefinitions()` automatically.
5. Return a summary message via the hook's return value so `drush updb` shows how many old values were logged.

**No auto-parsing of `"400W"` → `(400, "watts")`.** Real-world free-text from operators ("400w (2 panels)", "~400W", "400 watts") defeats any regex. An incorrect parse silently storing `(400, "gallons")` instead of `(400, "watts")` is worse than NULL. Existing data is logged and operators re-enter.

### JSON:API Impact

This is a **breaking change in field names and shape**:

Before:
```json
{"capacity": "400W"}
```

After:
```json
{"capacity_value": "400.000", "capacity_unit": "watts"}
```

There are no confirmed external consumers of the Infrastructure JSON:API endpoint today. The field had a TODO in the source from day one, so anyone who built against it knew it was unstable. Note this in the PR description and the CHANGELOG entry.

### Kernel Test Updates

In `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php`:

- `testInfrastructureFieldsRegistered()`: remove `'capacity'`, add `'capacity_value'` and `'capacity_unit'`. Update docblock from "6 Infrastructure custom fields" to "7 Infrastructure custom fields."
- Add `testCapacityValueRangeValidation()`: parallel to the existing Tree decimal range tests at lines 181-201. Negative `capacity_value` produces a validation violation; `0` and positive values pass.

## Decisions Made

| Question | Decision | Rationale |
|---|---|---|
| Pairing enforcement (value without unit, or vice versa) | **No constraint.** Partial entry allowed. | YAGNI — defer until real data shows the problem. Custom validation constraint is its own implementation cost. |
| Unit list completeness | Ship the 10 units in the design. | Covers solar/irrigation/fencing/electrical. Adding a unit is a 1-line edit; no need to gold-plate upfront. |
| Upper bound on `capacity_value` | No `max` set. | A 400 W panel and a 15,000 gal cistern share this field. Per-unit caps would need a custom validator and aren't worth the cost. |
| Auto-parse existing free-text data | No. | Real-world inputs defeat any regex; misclassification is worse than NULL. |
| QGIS impact | Out of scope. | No current QGIS layers reference `capacity`; if any future style does, that PR updates them. |

## Open Questions for Implementation Phase

These are *not* design questions — they're confirmations to make during plan-writing:

1. **Confirmed production Infrastructure assets?** Before the update hook ships, run `drush sql-query "SELECT COUNT(*) FROM asset WHERE type='infrastructure';"` against any non-development instance. Expected: zero. If zero, the warning-log step is dead code and can simplify.
2. **`FieldStorageConfig::loadByName()` for bundle fields?** Need to confirm the right delete API for fields created via `bundleFieldDefinition()` in a plugin's `buildFieldDefinitions()` — these are *bundle* fields, not configurable fields, so the deletion path may differ from `FieldStorageConfig`. Worst case: `\Drupal::entityDefinitionUpdateManager()->uninstallFieldStorageDefinition()`.
3. **`drush updb` ordering vs. config import?** The `hook_update_N` runs *before* config import on update. If the new fields are picked up from PHP `buildFieldDefinitions()` rather than from `config/install/`, this should be fine, but verify with a dry-run on the smoke-test stack.

## Files Touched

- `modules/farm_syntropic/src/Plugin/Asset/AssetType/Infrastructure.php` — replace `capacity` with two new fields, remove the `TODO(phase-2)` block
- `modules/farm_syntropic/farm_syntropic.install` — **new file** containing `farm_syntropic_update_8001()` and an `@file` docblock
- `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php` — update expected fields, add range test
- `CHANGELOG.md` — Unreleased section: Added (two new fields), Changed (Infrastructure JSON:API breaking change), notes about the migration hook

## CI Impact

- `farm_syntropic CI (fast)` — should pass without changes (the new field schema is structurally the same as Tree's decimal+list_string)
- `farm_syntropic PHPStan` — should pass; the new code uses existing patterns
- `farm_syntropic smoke test` — should pass; the smoke test doesn't currently touch the `capacity` field. May want to add a sanity-check step that asserts the new fields are present, mirroring the existing Tree field assertion.
