# Animals

farmOS can be used to manage animal/livestock records.

An "Animal" asset type is provided for representing animal records, and all of
the standard farmOS [log types] can be used to record events and activities.

Animal records can be used to represent either individual animals, or multiple
animals (see "Inventory / head counts" below).

## Species/breeds

When you create an animal asset, you will need to define what **species/breed**
it is. Species/breeds represent the various **types** of animals you manage.
These can be very general names (eg: "Cattle") or more specific breeds (eg:
"Jersey cattle").

## Animal groups/herds

Animals can also be organized into groups using the [Group] asset type. This is
useful if you always manage certain animals together. It is also possible to
assign animals to more than one group. This can be used in many different ways
to help manage large numbers of animals in farmOS. See the [Group] asset guide
to learn more.

## Inventory / head counts

A single animal record can be used for managing more than one animal. This is
useful in cases where animals don't need to be tracked individually, for
instance with flocks of birds or heads of cattle (where individual tagging is
not necessary for record keeping purposes).

To learn how to use inventory adjustments to track animal head counts over
time, read the [inventory] use guide.

> Q: Should I use inventory or groups for my animals?

This comes down to whether or not you need to maintain separate records for
individual animals. If you do, then create a separate animal asset for each
animal, and you can optionally organize them into group assets after that. If
you don't need individual animal records, you can create a single animal asset
and use the inventory features to track a head count over time with logs. Or,
you could do both! Perhaps you have a herd (group asset) with some individual
animals, and some larger groups of animals (head count). It's just a matter of
how granular you need your record keeping to be.

## Movements

Animals can be moved from place to place in farmOS using [movement logs]. You
can also filter your animal list down to animals within a certain group, select
all, and create a combined movement log for all of them at once. This is a
great way to manage grazing records as you move animals from paddock to
paddock. For more general information on moving assets in farmOS, read the page
on [movements and location].

## Medical records

In addition to the standard [log types] that all farmOS assets share
(activities, observations, inputs, and harvests), the livestock module provides
an additional log type that is specific to animals: medical records.

**Medical** logs can be used to record animal health records. This can be a
veterinary visit, administering medicine/vaccinations, or other medical
procedures. You can also use standard **Input** logs when administering
medicine or vaccinations, if you prefer, and reserve **Medical** logs for more
serious events/procedures.

[log types]: /guide/logs
[Group]: /guide/assets/groups
[movement logs]: /guide/location
[movements and location]: /guide/location
[inventory]: /guide/inventory

