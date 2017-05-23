# Entity types

farmOS is architected using four Drupal entity types:

1. Farm Assets
2. Logs
3. Taxonomy Terms
4. Users

The Entity system in Drupal provides a standardized way of representing
different types of objects, and includes mechanisms and APIs for adding fields,
creating relationships to other entities, and performing actions when entities
are created/displayed/updated/deleted. farmOS uses Drupal's entity system to
represent all of its records.

The first three types (Farm Assets, Logs, and Taxonomy Terms) also use a Drupal
concept known as "bundles". Bundles are essentially sub-types. Each bundle can
have a unique set of input fields on it, which can be used to create different
types of assets, logs, etc.

To learn more about how entity types and bundles work in Drupal, refer to
[An Introduction to Entities] in the drupal.org handbook.

The following is a brief overview of the entity types that farmOS uses.

## Farm Assets

The "Farm Asset" entity type is provided by the [Farm Asset module], and is used
to represent "assets" or "things" in the farm. farmOS comes with a core set of
asset types, including Plantings, Animals, and Equipment - and more can be added
via contributed modules.

## Logs

The "Log" entity type is provided by the [Log module], and is used to represent
various types of events that are recorded on a farm (ie: activities,
observations, inputs, harvests, etc).

The [Farm Log module] provides a [core set of log types] that can apply to any
kind of asset. Other modules can provide more specific log types - like the
"Seeding" log type, which is provided by the [Farm Crop module].

## Taxonomy Terms

Taxonomy Terms are a core Drupal entity type, and they are generally used for
categorization and tagging of things. Taxonomy Terms are organized into
"Vocabularies", and farmOS provides a number of different vocabulary types that
are used throughout the system.

## Users

Users are a core Drupal entity type, and they provide the mechanism through
which you can log into the system and use it. They represent all the different
people who are involved with the farm, and they can be assigned roles to grant
them different [levels of permission].

[An Introduction to Entities]: http://www.drupal.org/node/1261744
[Farm Asset module]: https://drupal.org/project/farm_asset
[Log module]: https://drupal.org/project/log
[Farm Log module]: https://drupal.org/project/farm_log
[core set of log types]: /guide/logs
[Farm Crop module]: https://drupal.org/project/farm_crop
[levels of permission]: /guide/roles

