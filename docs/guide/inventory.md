# Inventory tracking

Every Asset in farmOS can have one or more inventory levels associated with it.
These are tracked via Quantities on Logs, allowing for inventory adjustments to
be tracked over time. Inventory levels can be incremented, decremented, or
reset by a Log.

To adjust the inventory of an Asset, enable the Inventory module, and create a
Log with a Quantity. Within the Quantity, select "Increment", "Decrement", or
"Reset" in the "Inventory adjustment" options, and then select the Asset whose
inventory you would like to adjust. Fill in the Quantity's value (and
optionally its measure, units, and label), set the Log's status to "complete",
and then save the Log. Browse to the Asset you selected, and you will see the
new inventory level.

Multiple inventory levels can be tracked on each Asset, based on the measure
and units specified in the Quantity. Each measure+unit pair (or lack thereof)
will be counted as a separate inventory level.

For more information, refer to the [inventory logic](/model/logic/inventory)
section of the [farmOS data model](/model) docs.
