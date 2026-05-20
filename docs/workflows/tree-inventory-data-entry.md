# Tree Inventory Data Entry Workflow

**Purpose:** Step-by-step guide for recording individual orchard trees in AgriforestryOS (farmOS).
**Operator:** Wes
**Equipment:** Phone/tablet with GPS, browser open to farmOS

---

## Before You Start

1. Open farmOS at `http://<farmos-url>` on your phone/tablet
2. Log in with your farmOS account
3. Confirm GPS is enabled on your device
4. Have the orchard map/section plan handy for reference

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

Create individual Tree assets only for trees that need individual tracking (a dead tree, a grafted variant, a sensor-mounted specimen). On each Tree asset, set the **Tree Planting** field to point back to this planting.

A "Trees in this Planting" Drupal View is installed (`tree_planting_trees`). It can be placed as a block on the Tree Planting asset page via Drupal's Structure → Block Layout UI — the block is not auto-placed in v1.

You do not need to create individual Tree assets for every tree in a batch — the planting itself is the record.

---

## For Each Tree

### Step 1: Navigate to the tree

Walk to the tree. Ensure you are standing at or near the trunk.

### Step 2: Create a new Tree asset

1. Tap **+ Add Asset** (or navigate to `/asset/add/tree`)
2. Select **Tree**

### Step 3: Fill in required fields

| Field | What to enter | Example |
|-------|--------------|---------|
| **Name** | Row + position or unique ID | `Row A - Tree 3` |
| **Species** | Select from existing terms (preferred) or type to create new | `American Chestnut` |

> **Tip:** When entering Species (or any taxonomy field), **always pick an
> existing term from the autocomplete dropdown** if one matches. Typing a new
> name creates a new taxonomy term automatically — a typo like `"Amercan
> Chestnut"` becomes a permanent orphan term that has to be cleaned up later.

> **Archive:** Trees are kept as Active by default. To archive a tree that is
> dead, removed, or otherwise no longer in service, toggle **Archived** in the
> right-hand meta sidebar before saving.

### Step 4: Fill in recommended fields (if known)

| Field | What to enter | Notes |
|-------|--------------|-------|
| **Variety** | Cultivar name | `Dunstan` |
| **Health status** | Visual assessment | `Good`, `Fair`, etc. |
| **Stratum** | Canopy layer | `High Canopy`, `Shrub`, etc. |
| **Planting date** | Date and time (both required) | `2025-03-15`, `00:00` |
| **Source** | Where the tree came from | `At the Grove Nursery` |
| **DBH (cm)** | Measure at ~1.4m height | `15.5` |
| **Height (m)** | Estimate | `4.2` |

### Step 5: Set GPS coordinates

1. Click the **Location** tab on the left sidebar
2. Toggle **Is fixed** to ON — this reveals the Intrinsic geometry section
3. Choose one of these methods to set the point:

**Option A — Map tap (recommended for phone):**
1. The map should center near your current GPS location
2. Use the point tool (dot icon) in the map toolbar
3. Tap the map at the tree's trunk location
4. A point marker appears — adjust if needed

**Option B — Manual WKT (for precision):**
1. Use a GPS app to get exact coordinates
2. In the text area below the map, enter: `POINT(-82.551234 35.601567)`
3. Format: `POINT(longitude latitude)` — longitude comes first

### Step 6: Save

Tap **Save**. The tree is now recorded with its GPS position.

### Step 7: Add a photo (optional but recommended)

1. Open the saved tree asset
2. Scroll to **Images**
3. Tap **Add image** and take a photo of the tree

---

## Handling Special Cases

### Tree is already in Odoo inventory (has a lot number)
- Enter the Odoo lot/serial number in the **Odoo lot/serial** field
- This links the farmOS tree record back to the Odoo inventory record
- The sync service will use this field to avoid creating duplicates

### Tree is not in any inventory (newly discovered)
- Leave **Odoo lot/serial** blank
- Enter `Unknown` for **Source** if origin is unknown
- Enter the best guess for **Species** — it can be updated later

### Tree is dead or removed
- Toggle **Archived** in the right-hand meta sidebar
- Set **Health status** to `Dead` or `Removed`
- Still record GPS coordinates — the location data is valuable for replanting plans

### Group of trees (same species in a row or block)

Use the **Tree Planting** path described in "When to use Tree Planting first" above. A single Tree Planting asset records the species, count, spacing, and geometry for the entire batch.

If you have already entered individual trees without a parent planting, you can retroactively link them: open each Tree asset and set the **Tree Planting** field. The data model supports this; no migration is required.

---

## Field Naming Convention

Use this pattern for tree names:

```
[Section] - [Row/Area] - Tree [Number]
```

Examples:
- `Orchard North - Row A - Tree 1`
- `Orchard South - Block 2 - Tree 15`
- `Perimeter - Fence Line East - Tree 3`

---

## End of Session

After completing a section:
1. Navigate to **Assets > Tree** to see your inventory list
2. Verify the map view shows all trees with correct positions
3. Note any trees you couldn't identify (species unknown) for follow-up

---

## Quick Reference: Required vs. Optional Fields

| Field | Required? | Can fill later? |
|-------|-----------|----------------|
| Name | Yes | — |
| Species | Yes | — |
| GPS (Geometry) | No but strongly recommended | Yes |
| Archived (sidebar toggle) | No (defaults to off) | Yes |
| Variety | No | Yes |
| Health status | No | Yes |
| Stratum | No | Yes |
| Succession stage | No | Yes |
| DBH / Height / Canopy | No | Yes |
| Planting date | No | Yes |
| Source | No | Yes |
| Odoo lot/serial | No | Yes (sync fills this) |
