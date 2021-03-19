# Group membership

farmOS includes an optional "Group asset" module, which adds a new Asset type
called Group, as well as some organizational features that are unique to Group
Assets.

A Group can have "members", which are other individual Assets. Group membership
is tracked via "group assignment" Logs. This is very similar to the way that
[Location](/model/logic/location) works for "movable" Assets. This means that
an Asset's group membership can change, and the full history of its previous
memberships is maintained in Logs.

A useful feature of Groups is that any Logs associated with the Group will also
be associated with its member Assets.

A common use case for this is tracking "herds" of Animal assets. Each herd can
be represented as a Group Asset, with Animal Assets assigned to it. Logs can be
used to move the whole group, instead of referencing Animal Assets individually.

Notably, when the Group module is enabled, it overrides the Asset location
logic to consider group membership. If the Asset is a member of a group, and
the group has a movement Log that is more recent than any of the Asset's own
movement Logs, then the Asset's location will be based on the group's Log.

## Group membership logic

The logic for determining an Asset's group membership is as follows:

- *Does the asset have a group assignment Log?*
    - Yes: *Does the group assignment Log reference groups?*
        - Yes: **groups referenced by the group assignment Log**
        - No: **no group**
    - No: **no group**
