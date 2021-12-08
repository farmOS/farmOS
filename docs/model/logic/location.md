# Location

farmOS can track the physical location of Assets over time. This includes map
geometry as well as relation to other Assets.

## Fixed Assets

Some Assets are *fixed* in location. The geometry of these Assets is
*intrinsic* to the Asset itself.

Land is an example of an Asset with a fixed location. Although geography
changes over time, land assets in farmOS should be considered a snapshot of the
physical land they represent at the current time. This is useful because it
provides a simplified model for referring to a logical piece of land and its
geometry.

Changes to a fixed Asset's intrinsic geometry should be limited to minor
updates or corrections. If the changes are more substantial, a new Asset should
be created, and the old Asset archived, to demarcate it as a new phase of the
place in question.

## Movable Assets

If an Asset is not fixed, then it is considered *movable*, and its location and
geometry will be determined by *movement* Logs that reference it.

In order to set or change a movable Asset's location, a movement Log must be
created that defines its new location at that point in time.

Any Log can be a movement. It just needs to reference the Assets that are
moving, along with their new location. The new location can be in the form of a
geometry and/or a reference to one or more location Assets.

It is possible to find an Asset's location at any point in time by querying its
movement Logs.

Generally a movement Log is only included if it is "done", although it is also
possible to find "pending" movement Logs in the future to find an Asset's
projected future location.

## Locations

Assets can be designated as *locations*. This allows other Assets to be moved
to them.

Typically, fixed Assets will also be designated as locations. However, movable
Assets can also be locations. Consider a tractor (Equipment Asset) with
multiple attachments (more Equipment Assets). It is possible to record that an
attachment is connected to the tractor, and therefore its location is derived
from the tractor's location.

Fixed location Assets can designate *parent* Assets to create a hierarchical
organization.

## Logic

The logic for determining an Asset's geometry is as follows:

- *Is the Asset fixed?*
    - Yes: *Does it have an intrinsic geometry?*
        - Yes: **intrinsic geometry**
        - No: **no geometry**
    - No: *Does it have a movement Log?*
        - Yes: *Does the movement Log have geometry?*
            - Yes: **movement Log geometry**
            - No: *Does the movement Log reference a location Asset?*
                - Yes: **(recurse to determine location Asset's geometry)**
                - No: **no geometry**
        - No: **no geometry**

## Geometry

When a Log is saved without a geometry, and it references locations that have
geometries, the combined geometries of the referenced locations will be copied
to the Log. Assets referenced in the Location relationship are given first
priority. If none are found, then Assets referenced in the Log's Asset
relationship will be used. In the latter case, only Assets that are explicitly
designated as locations will be included, and geometry will not be copied if
the Log is a movement (otherwise it would be impossible to clear the geometry
of a non-fixed location Asset via movement Logs).
