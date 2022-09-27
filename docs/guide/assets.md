# Managing Assets

All the important and valuable things on your farm are represented as "Assets"
in farmOS. Asset types include Land, Plants, Animals, Equipment, Structures,
etc. Assets can be accessed from "Records > Assets" in the toolbar.

Assets can be archived, so they do not show in farmOS unless you specifically
want to see them. For example, when you are done harvesting a "Plant" Asset,
you can mark it as "archived" to hide it in your Asset lists. Archived records
can be retrieved using the "Filters" options on Asset listing pages.

Assets can be designated as "locations", which will cause them to show up in
the "Locations" hierarchy (available from the toolbar). It also allows other
Assets to be moved to them. For example, a "Plant" or "Animal" Asset can be
moved to a "Land" Asset. The hierarchy of location Assets is determined by
their "parent" field relationships.

For more information on Asset location, refer to the
[location logic](/model/logic/location) section of the
[farmOS data model](/model) docs.

Assets can also be cloned by selecting one or more in a list and clicking the
"Clone asset" button that appears at the bottom. This will clone the Asset
record(s), but will not clone the Logs that are associated with them.

Assets can be assigned to one or more person(s) in farmOS using the Asset's
*Owner* field. Users can view a list of all Assets assigned to them by
navigating to their profile and then clicking the "Assets" tab.

For more information on Asset records, refer the [Assets](/model/type/asset)
section of the [farmOS data model](/model) docs.

## Asset types

farmOS comes with a number of Asset types, summarized below. Additional types
can be added via modules.

### Land

Land Assets represent the fields, properties, beds, paddocks, etc that are
being managed. They can be mapped and arranged hierarchically to make
navigation easier, and they can be referenced by Logs to record events,
activities, inputs, observations, etc. If you perform soil tests, these
can be stored as "Lab test" Logs associated with land Assets for easy
access.

### Plants

farmOS can be used for very fine-grained crop planning and record keeping. It
can be used by large or small operations, nurseries, seed producers, breeders,
and home gardeners.

The Plant Asset type can be used to represent groups of plants (eg: a field of
corn, or a group of seedlings), or it can be used to represent individual plants
(eg: in the case of nurseries).

When you create a plant Asset, you will need to define what **crop/variety** it
is. Crops/varieties represent the various **types** of plants you grow. These
can be very general crop names (eg: "Broccoli") or very specific breeds or
varieties (eg: "Belstar F1 Organic Broccoli"). Your Crops/varieties taxonomy
can be managed/organized as a hierarchy in Administration > Structure >
Taxonomy.

Plants are the specific Asset you are growing, whereas crops/varieties are terms
used to categorize and define plant types. You may have multiple plant Assets of
the same crop/variety. Consider the following example:

> 1 pound of red lettuce seed was purchased and seeded 4 times over the course
> of 8 weeks (every two weeks).

In this example, there would be 4 plant Assets with a crop/variety of "Red
lettuce":

- 2017 Red lettuce planting 1
- 2017 Red lettuce planting 2
- 2017 Red lettuce planting 3
- 2017 Red lettuce planting 4

The way you name your Assets is up to you - this is just an example. Including
the year at the beginning and the planting number at the end is helpful when
you are trying to distinguish plant Assets from one another.

[Seeding](/guide/logs/#seedings) and [Transplanting](/guide/logs/#transplantings)
Logs can be created in reference to plants.

If you are direct seeding into the field, you may only use the seeding Log. If
you are purchasing starts from another grower, you may only use the
transplanting Log. If you are starting your plants in a greenhouse and then
planting them out in the field, you may use both a seeding and transplanting
Log.

Together, seeding and transplanting Logs allow you to keep track of where a
plant has moved throughout its life. For more general information on tracking
the location of Assets in farmOS, refer to the overview of
[movements and location](/guide/location).

### Animals

farmOS can be used to manage animal/livestock records.

When you create an animal Asset, you will need to define what **species/breed**
it is. Species/breeds represent the various **types** of animals you manage.
These can be very general names (eg: "Cattle") or more specific breeds (eg:
"Jersey cattle"). Your Species/breeds taxonomy can be managed/organized as a
hierarchy in Administration > Structure > Taxonomy.

Animal Assets can be used to represent either individual animals, or multiple
animals (as a head count inventory, adjustable via Logs). This is useful in
cases where animals don't need to be tracked individually, for instance with
flocks of birds or heads of cattle, where individual tracking/tagging is not
necessary for record keeping purposes.

For more information, see the guide to [inventory tracking](/guide/inventory)
in farmOS.

Animals can also be organized into groups using the "Group" Asset type. This is
useful if you always manage certain animals together, as a herd or flock, for
instance. It is also possible to assign animals to more than one group. This can
be used in different ways to help manage large numbers of animals in farmOS.

> Q: Should I use inventory or groups for my animals?

This comes down to whether or not you need to maintain separate records for
individual animals. If you do, then create a separate animal Asset for each
animal, and you can optionally organize them into group Assets after that. If
you don't need individual animal records, you can create a single animal Asset
and use the inventory features to track a head count over time with Logs. Or,
you could do both! Perhaps you have a herd (group Asset) with some individual
animals, and some larger groups of animals (head count). It's just a matter of
how granular you need to be with your record keeping.

Animals can be moved from place to place in farmOS using movement Logs. You
can also filter your animal list down to a set of animals, select them all, and
create a combined movement Log for all of them at once. Or, you can move the
group Asset that contains animals (eg: a herd), and all the animal locations
will be updated along with it. This is a great way to manage grazing records as
you move animals from paddock to paddock.

For more general information on tracking the location of Assets in farmOS,
refer to the overview of [movements and location](/guide/location).

[Medical](/guide/logs/#medical) and [Birth](/guide/logs/#births) Logs can be
created in reference to animals.

### Equipment

farmOS can be used to manage equipment Assets on the farm.

[Maintenence Logs](/guide/logs/#maintenance) can be recorded alongside
equipment.

When you are creating Logs in farmOS, you can also reference the equipment
Asset(s) that were used to perform the activity. In combination with the
"Assets" field on Logs, this allows you to distinguish which Assets
**received** the action, and which equipment Assets **performed** it. These
"equipment use" Logs will appear on the equipment Asset record in farmOS.

Suggested uses:

* Use activity Logs to record equipment use.
* Keep track of equipment location via movement Logs.
* Record oil changes, repairs, and inspections with maintenance Logs.
* Track fuel usage or machine hours with input or observation Logs.

### Structures

Structure Assets can be used to represent buildings, greenhouses, and other
permanent or movable structures on the farm.

Like equipment Assets, [maintenence Logs](/guide/logs/#maintenance) can be used
to record when upkeep/repairs are performed.

Greenhouses can be represented as structure Assets, with Logs used to record
activities and observations within them. This can be very powerful, especially
when combined with sensors, data streams, notifications, and custom modules
that communicate with actuators to automate fans, ventilation, and irrigation.

### Water

Water Assets can be used to represent fixed water features like lakes, ponds,
and streams, or they can be used to represent fixed or movable irrigation
systems. Equipment and Structure Assets can be used similarly, so it is up to
you to decide how you prefer to organize things.

### Material

Material Assets can be used to track inventory of various materials.

For more information, see the guide to [inventory tracking](/guide/inventory)
in farmOS.

### Seed

Seed Assets can be used to track seed inventory before it is used to create
Plant Assets (via seeding Logs). Seeds share the same **crop/variety**
taxonomy with plant Assets.

### Compost

farmOS can be used to manage all types of compost production activities. A
generic "Compost" Asset type is provided, which can be used with various Log
types to record activities, observations, inputs, harvests, etc. These compost
Assets can be used to represent a compost pile,
[windrows](https://en.wikipedia.org/wiki/Windrow_composting),
[vermicompost](https://en.wikipedia.org/wiki/Vermicompost),
[compost tea](https://soiltest.uconn.edu/factsheets/composttea.pdf),
or any other form of production.

### Sensors

In addition to manually-entered records, farmOS also provides a framework for
receiving data from automated environmental sensors. The Sensor Asset type can
be used to represent individual sensor devices, which can provide one or more
[Data streams](/model/type/data_stream).

A "Basic" data stream type is provided, which stores data in the local database.
Add-on modules can be written to integrate with other data storage systems, or
provide custom sensor integrations.

It is possible to assemble your own sensors with inexpensive components and
send their data to farmOS without any soldering or programming.

### Groups

Groups are a special type of Asset in farmOS, which are used for organizing
other Assets, so they can be managed together.

This provides a lot of flexibility in managing and organizing your Assets.
Logs can reference the group Asset, instead of all the individual members.

One example where this is useful is managing herds of animals. Each herd can
be represented as a group Asset, with animal members. A movement Log can be
used to move the whole group, instead of referencing animal Assets
individually.

You can also use groups to organize equipment, plants, or any other Asset type.
It's even possible to have groups within groups, to create a hierarchy of group
membership.

Group membership is assigned to Assets via Logs, in very much the same way that
[location](/guide/location) is. You can assign Assets to a group via the
"Group" and "Is group assignment" fields on Logs. These specify that any
Asset(s) referenced by the Log will become members of the group at the time of
the Log.

Therefore, Assets can also change their membership over time, moving from one
group to another. One example where this is useful is in managing cattle: you
may have a group of mothers with calves, a group of weaned calves, and other
groups of steers, heifers, etc. As a calf grows up, weans, and perhaps has
their own calf, they can be moved from group to group, and the full history of
their group membership is saved as Logs.

For more information, refer to the [group membership logic](/model/logic/group)
section of the [farmOS data model](/model) docs.
