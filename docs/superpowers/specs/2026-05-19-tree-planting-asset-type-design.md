# Tree Planting Asset Type — Design

**Date:** 2026-05-19
**Status:** Approved (defaults applied), ready for `writing-plans`
**Scope:** `modules/farm_syntropic/` only — no upstream contribution

## Problem

Wes records orchard inventory in rows: "Row A: 12 American Chestnut trees on 3m spacing." Today's workflow requires 12 separate Tree-asset form submissions, one per tree, with no shared data model binding them together. This is ~10× too slow for a real orchard and loses the abstraction that these 12 trees ARE a row. The workflow doc already anticipates this spec ("For Phase 2: grouped plantings will use the Tree Planting asset type").

## Goals

- Single-form submission for a row or block of N trees of one species/variety.
- Bidirectional navigation between a Tree Planting (group) and its individual Tree assets.
- Preserve all existing Tree assets — operators who pre-entered individual trees can retroactively assign them to a Planting.
- Support both row (`LineString`) and block (`Polygon`) geometries.
- Make "how many chestnuts in Row A?" answerable via a single JSON:API query.

## Non-Goals

- Auto-generation of child Tree assets from a Planting (that's a future workflow feature, not data model).
- Bulk-assignment UX for existing Tree assets (deferred to a follow-up PR; data model supports it).
- Real-time computed `tree_count` from live children (declared count + manual updates is the v1 design).
- Harvest/seeding/seasonal log management (handled by farmOS Logs in Phase 2).
- Upstream contribution.

## Design

### New Asset Bundle

A new `#[AssetType(id: 'tree_planting')]` plugin class following the exact pattern of `Tree.php` and `Infrastructure.php`.

**File:** `modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php`

**Config entity:** `modules/farm_syntropic/config/install/asset.type.tree_planting.yml` with a fresh UUID, `id: tree_planting`, `label: Tree Planting`, `description: 'A row or block of trees planted as a batch. Parent record for individual Tree assets.'`, `new_revision: true`.

### Field Schema (8 fields on TreePlanting)

| Field | Type | Required | Notes |
|---|---|---|---|
| `species` | `entity_reference` → `taxonomy_term` / `plant_type` | Yes | Same target as `Tree::species`. `auto_create: TRUE`, `multiple: FALSE`. Weight `-90` |
| `variety` | `string` | No | Cultivar name for the batch. Weight `-85` |
| `tree_count` | `integer` | Yes | `min: 1`. Declared count at planting time. Drifts from reality as trees die — operator manually updates. Weight `-80` |
| `spacing_m` | `decimal` | No | Precision 6, scale 2, `min: 0`. Applies to row plantings; blank for blocks. Weight `-75` |
| `geometry` | `geofield` | No | `LineString` for rows, `Polygon` for blocks. Set `is_fixed: TRUE` by default on new Tree Planting assets. Open-ended WKT — no geometry-type validator (keep flexible). Weight: location tab per farmOS convention |
| `planting_date` | `timestamp` | No | Date the batch went in. Weight `-70` |
| `source` | `string` | No | Nursery, seed source, or propagation method for the batch. Weight `-65` |
| `notes` | `text_long` | No | Free-form notes about the planting event. Weight `-10` |

### New Field on Tree (for reverse navigation)

Add a `parent_planting` field to `Tree::buildFieldDefinitions()`:

```
'parent_planting' => [
  'type' => 'entity_reference',
  'label' => $this->t('Tree Planting'),
  'description' => $this->t('The grouped planting this tree belongs to.'),
  'target_type' => 'asset',
  // Bundle constraint: only accept tree_planting assets.
  // Implementation detail: see Open Questions #1.
  'multiple' => FALSE,
  'weight' => ['form' => -80, 'view' => -80],
],
```

The field is **typed to only accept `tree_planting` assets** to encode the semantic and enforce the constraint at the storage level. (Contrast with farmOS's generic `parent` field, which accepts any asset bundle.)

### Bidirectional Relationship

**Forward (Tree Planting → Trees):** An embedded Drupal view on the Tree Planting asset page that queries `asset` entities filtered by `type = tree` AND `parent_planting = [current asset ID]`. Display-layer concern, no new storage.

**Reverse (Tree → Tree Planting):** The new typed `parent_planting` entity_reference field above. Direct lookup.

**Why not the generic `parent` field from `farm_parent`?** The existing `parent` base field is global across all asset types with no bundle constraint mechanism. Using it would allow attaching a tree to an equipment asset or a land asset. The typed `parent_planting` field encodes the semantic and enforces the constraint.

### JSON:API Shapes

`GET /jsonapi/asset/tree_planting/{uuid}?include=species`

```json
{
  "data": {
    "type": "asset--tree_planting",
    "id": "<uuid>",
    "attributes": {
      "name": "Row A - American Chestnut",
      "tree_count": 12,
      "spacing_m": "3.00",
      "planting_date": 1742000000,
      "source": "At the Grove Nursery",
      "variety": "Dunstan",
      "notes": "Planted on contour, 3m spacing, healthy at install."
    },
    "relationships": {
      "species": {"data": {"type": "taxonomy_term--plant_type", "id": "<species-uuid>"}}
    }
  }
}
```

**MCP/JSON:API query "list trees in Row A":**
```
GET /jsonapi/asset/tree?filter[parent_planting.id]={planting_uuid}&include=species
```

**MCP query "how many chestnuts in Row A":**
- Declared: read `tree_count` from the Planting's attributes (one round trip)
- Current (excluding dead/removed): `GET /jsonapi/asset/tree?filter[parent_planting.id]={uuid}&filter[status]=active` and read `meta.count`

### Operator Workflow Update

Update `docs/workflows/tree-inventory-data-entry.md`. Insert a new top-level section *before* "For Each Tree":

**When to use Tree Planting first (recommended for new rows/blocks)**

1. Navigate to `/asset/add/tree_planting`.
2. Name the planting: `Row A - American Chestnut` or `Orchard North - Block 2`.
3. Enter species, variety, tree count, spacing (for rows), and planting date.
4. Draw the row geometry as a `LineString` (tap two endpoints) or block as a `Polygon` on the map.
5. Save. The Tree Planting is your inventory record for the batch.
6. Optionally create individual Tree assets later for trees that need individual tracking (dead trees, grafted variants, sensor-mounted trees). On each Tree, set the **Tree Planting** field to point back to this record.

The existing "Group of trees (same species in a row)" special-case section: remove the "create one asset per tree" instruction; replace with a pointer to the new section. Keep the per-tree workflow but demote to a secondary path.

### Smoke Test Updates

In `.github/workflows/smoke-farm-syntropic.yml`:

1. **Extend the bundle check:** add `tree_planting` to the existing `$expected_bundles` array in the "Verify Tree and Infrastructure asset bundles" step.
2. **New step "Create Tree Planting and link Tree":** create a `tree_planting` asset, then create a `tree` asset with `parent_planting` pointing at it, reload, assert `parent_planting->target_id` matches. Confirms bidirectionality at the entity level.
3. **New step "Verify JSON:API exposes tree_planting":** `curl /jsonapi/asset/tree_planting`, assert `data` contains the planting we just created with `tree_count` and `spacing_m` in attributes.

### Kernel Test Updates

In `FarmSyntropicFieldSchemaTest`:

1. `testAssetBundlesRegistered`: add `tree_planting` to the expected list.
2. New test method `testTreePlantingFieldsRegistered()`: assert all 8 TreePlanting fields are present on the bundle.
3. New test method `testTreeParentPlantingFieldRegistered()`: assert the new `parent_planting` field exists on Tree.
4. Update `testTreeFieldsRegistered()` to include `parent_planting` in the expected list (count goes 13 → 14).
5. Add `testTreePlantingTreeCountValidation()`: `tree_count = 0` produces a violation; `tree_count = 1` passes.

## Decisions Made

| Question | Decision | Rationale |
|---|---|---|
| New bundle vs. extend `plant` vs. use generic `parent` | **New bundle** (`tree_planting`) | `plant` is semantically about annuals; the generic `parent` has no bundle constraint and no place for group-level fields. New bundle matches the established pattern (Tree, Infrastructure). |
| `tree_count` declared vs. computed | **Declared only** | Simpler. Documented that drift is expected; ops manually update. Phase 2 can add a computed field if needed. |
| Geometry type enforcement (LineString-only or Polygon-only) | **No restriction** — open-ended WKT | Maximum flexibility. A future custom validator can lock it down if operators consistently misuse the field. |
| Retroactive bulk-assignment UX for existing Tree assets | **Defer to follow-up PR** | Data model supports it via the new field; UX scope is separate. v1 ships the field; bulk-edit can be a Drupal Views action later. |
| `parent_planting` bundle constraint enforcement | Typed at field definition | Field's `handler_settings.target_bundles` constrains to `[tree_planting]`. Validated via the smoke-test relationship step. |

## Open Questions for Implementation Phase

These are *not* design questions — they're confirmations to make during plan-writing:

1. **`FarmFieldFactory::modifyEntityReferenceField()` bundle support for asset targets** — does the factory honor `target_bundles` when `target_type` is `asset` rather than `taxonomy_term`? If yes, the shorthand in `Tree::buildFieldDefinitions()` works. If no, fallback to `modules/farm_syntropic/config/install/field.field.asset.tree.parent_planting.yml` for the field instance override.
2. **Embedded view on Tree Planting page** — Drupal view config entity (preferred) or hard-coded block in a `hook_entity_view_alter`? Plan should choose; spec stays neutral.
3. **`smoke-farm-syntropic.yml` auth scope** — confirms `parent_planting` is recognized after `drush en farm_syntropic --yes` with no extra rebuild step.

## Files Touched

- `modules/farm_syntropic/src/Plugin/Asset/AssetType/TreePlanting.php` — **new file**
- `modules/farm_syntropic/src/Plugin/Asset/AssetType/Tree.php` — add `parent_planting` field
- `modules/farm_syntropic/config/install/asset.type.tree_planting.yml` — **new file**
- (possibly) `modules/farm_syntropic/config/install/field.field.asset.tree.parent_planting.yml` — fallback if factory doesn't support asset-bundle constraints; decide during plan
- (possibly) `modules/farm_syntropic/config/install/views.view.tree_planting_trees.yml` — embedded view, or
- `modules/farm_syntropic/farm_syntropic.module` — `hook_entity_view_alter` to inject the trees-in-this-planting list
- `modules/farm_syntropic/tests/src/Kernel/FarmSyntropicFieldSchemaTest.php` — new tests
- `.github/workflows/smoke-farm-syntropic.yml` — new steps
- `docs/workflows/tree-inventory-data-entry.md` — new section, demote per-tree path
- `CHANGELOG.md`

## CI Impact

- `farm_syntropic CI (fast)` — passes (no workflow changes; PHP/YAML/actionlint coverage extends naturally)
- `farm_syntropic PHPStan` — should pass; plugin pattern unchanged
- `farm_syntropic smoke test` — extended with the new bundle + relationship + JSON:API steps
