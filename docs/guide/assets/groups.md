# Groups

A **Group** asset type is provided for organizing other assets into groups.
This provides a lot of flexibility in managing and organizing your assets.
[Logs] can reference the group asset, instead of all the individual members.

This is useful when managing herds of animals. Each herd can be represented as
a group asset, with animal records assigned to it. A movement log can be used
to move the whole group, instead of referencing animal assets individually.

You can also use groups to organize equipment, plantings, or any other asset
type. It's even possible to have groups within groups, to create a hierarchy
of group membership.

Group membership is assigned to assets via logs, in very much the same way that
[location] is. You can assign assets to a group via the "Group membership"
fields on Activity and Observation logs. This specifies that the asset(s)
became members of the group at the time of the log.

Therefore, assets can also change their membership over time, moving from one
group to another. One example where this is useful is in managing cattle: you
may have a group of mothers with calves, a group of weaned calves, and other
groups of steers, heifers, etc. As a calf grows up, weans, and perhaps has
their own calf, they can be moved from group to group, and the full history of
their group membership is saved as logs.

[Logs]: /guide/logs
[location]: /guide/location

