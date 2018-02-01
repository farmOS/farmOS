# Inventory tracking

A new feature available in farmOS 7.x-1.0-beta16 is an inventory module, which
allows for tracking of asset inventory levels over time via [logs]. As of this
release, inventory management is only enabled on [animal] assets, but will be
enabled on other asset types in future releases. For more information about
tracking animal inventory, read the [animal] asset user guide.

Inventory can be added/subtracted from an asset using the "Inventory
adjustment" fields on logs. You may also make more than one inventory
adjustment on an individual log (to different assets, for example).

The inventory adjustment field has two subfields: **Asset** and **Value**.

The asset field references the asset whose inventory is being adjusted. The
value field is a positive or negative adjustment to the asset's inventory. A
positive number will add to the inventory, and a negative number will subtract
from the inventory.

An asset's current inventory is visible on the asset record page. You can also
view a list of all logs that have adjusted the asset's inventory in the past
(as well as planned inventory adjustment logs in the future).

[logs]: /guide/logs
[animal]: /guide/assets/animals

