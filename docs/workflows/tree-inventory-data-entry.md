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
| **Species** | Select from dropdown or type new | `American Chestnut` |
| **Status** | Active, Planned, or Archived | `Active` |

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
- Set **Status** to `Archived`
- Set **Health status** to `Dead` or `Removed`
- Still record GPS coordinates — the location data is valuable for replanting plans

### Group of trees (same species in a row)
- Create one Tree asset per individual tree
- Use consistent naming: `Row A - Tree 1`, `Row A - Tree 2`, etc.
- For Phase 2: grouped plantings will use the Tree Planting asset type

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
| Status | Yes | — |
| GPS (Geometry) | No but strongly recommended | Yes |
| Variety | No | Yes |
| Health status | No | Yes |
| Stratum | No | Yes |
| Succession stage | No | Yes |
| DBH / Height / Canopy | No | Yes |
| Planting date | No | Yes |
| Source | No | Yes |
| Odoo lot/serial | No | Yes (sync fills this) |
