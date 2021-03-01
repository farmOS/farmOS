# Inventory

farmOS can track the inventory of Assets over time.

Inventory is tracked via [Logs](/model/type/log) with *inventory adjustment*
[Quantities](/model/type/quantity). The Inventory module adds two fields to
Quantity records: "inventory asset" and "inventory adjustment". Each Quantity
can reference a single Asset, and either "reset", "increment", or "decrement"
that Asset's inventory. The Quantity's "measure", "value", and "units" fields
are used in the inventory calculations.

Asset inventory is determined by querying all Quantities that reference the
Asset and define an adjustment type of "reset", "increment", or "decrement".
Inventory is calculated by adding all "increment" adjustments and subtracting
all "decrement" adjustments, starting from the most recent "reset" adjustment
(or zero if no "reset" adjustment exists).

A separate inventory is tracked for each measure+unit pair, so Assets can have
a single simple inventory (without a specified measure or unit), or they can
have multiple inventories of different measures and units.
