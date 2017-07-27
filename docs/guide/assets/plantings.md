# Plantings

farmOS can be used for very fine-grained crop planning and record keeping. It
can be used by large or small operations, nurseries, seed producers, breeders,
and home gardeners.

The asset type used to manage crops is called a **Planting**.

Plantings can be used to represent groups of plants (eg: a field of corn, or a
group of seedlings), or it can be used to represent individual plants (eg: in
the case of nurseries).

## Crops/varieties

When you create a planting asset, you will need to define what **crop/variety**
it is. Crops/varieties represent the various **types** of plantings you grow.
These can be very general crop names (eg: "Broccoli") or very specific breeds
or varieties (eg: "Belstar F1 Organic Broccoli").

Plantings are the specific asset you are growing, whereas crops/varieties are
used to categorize and define planting types. You may have multiple plantings
of the same crop/variety. Consider the following example:

> 1 pound of red lettuce seed was purchased and seeded 4 times over the course
> of 8 weeks (every two weeks).

In this example, there would be 4 planting assets with a crop/variety of "Red
lettuce":

* 2017 Red lettuce planting 1
* 2017 Red lettuce planting 2
* 2017 Red lettuce planting 3
* 2017 Red lettuce planting 4

The way you name your plantings is up to you - this is just an example.
Including the year at the beginning and the planting number at the end is
helpful when you are looking at long lists of plantings.

## Planting logs

In addition to the standard [log types] that all farmOS assets share
(activities, observations, inputs, and harvests), there are two log types that
are specific to plantings: seedings and transplantings.

**Seeding** logs represent when seeds were planted in the ground or in
containers. With a seeding log, you can specify the seeding quantity (eg: 100
lbs, 20 72-plug trays, etc), and you can specify where the seeding occurred
(using the [movement fields]) so that farmOS knows where the planting asset is
located.

**Transplanting** logs represent when a planting was transplanted from one
place to another. Similar to seeding logs, transplantings can have a quantity
and a location.

If you are direct seeding into the field, you may only use the seeding log. If
you are purchasing starts from another grower, you may only use the
transplanting log. If you are starting your plantings in a greenhouse and then
planting them out in the field, you may use both a seeding and transplanting
log.

[log types]: /guide/logs
[movement fields]: /guide/location

