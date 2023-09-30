# farmOS User Guide

## Introduction

Welcome to the official farmOS user guide. These documents are intended for
people who will be using farmOS for record keeping and farm management.

If you are looking for information about hosting farmOS, or contributing to
the development, refer to the dedicated documentation on those topics:

- [Hosting farmOS](/hosting)
- [Contributing to farmOS](https://github.com/farmOS/farmOS/blob/3.x/CONTRIBUTING.md)

## Logging in

The first step to using farmOS is logging in. All records are private by
default, and can only be viewed by people with a username and password.

To log in, first you need to know the address of your farmOS site. If you are
hosting farmOS on [Farmier](https://farmier.com), this address will most likely
be something like `https://myfarm.farmos.net` (where `myfarm` is the name of
your farm). If your farmOS is hosted by someone else, they will be able to point
you to the correct URL.

Enter the URL into the web browser on your computer, phone, or tablet, and you
should see a login form. Enter your username and password, and click "Log in"
to begin using farmOS.

## Dashboard

The first thing you will see when you log in is the farmOS dashboard.

On top is the farm map, where you will see all active location Assets that you
have mapped. You can use this to navigate to your records within farmOS by
clicking on a location and then clicking on the available links within the
Asset details popup.

Below the map you will see "Upcoming tasks" and "Late tasks" on the left, which
summarize Logs that are still in "pending" status. From here you can quickly
select Logs and mark them as "complete", reschedule them, clone them, or delete
them.

To the right you will see a summary of various farm metrics, including total
Asset and Log counts. Additional metrics can be added via modules.

## Navigation

farmOS is designed to make your records approachable from multiple angles, so
it is easy to find records you made in the past, and add new ones in the
future.

The toolbar on the left provides quick access to various records and tools:

- **[Quick forms](/guide/quick)** - Lists "quick forms" available for easy data
  entry of common records. This will only be visible if you have quick form
  modules enabled.
- **[Locations](/guide/mapping)** - Manage the hierarchy of location Assets.
- **Plans** - Provide high-level management of Assets and Logs around a
  particular purpose. This will only be visible if you have plan type modules
  enabled.
- **Records** - Direct access to raw lists of records by type and sub-type.
    - **[Assets](/guide/assets)** - These are the "management units" of farmOS.
      They represent the things of value that you are managing.
    - **[Logs](/guide/logs)** - These are the events that that place in
      relation to Assets.
    - **[Quantities](/guide/quantities)** - These represent granular
      measurements of quantitative data points associated with Logs.
- **Reports** - Lists "reports" available for easy data analysis. This will
  only be visible if you have report modules enabled.
- **[People](/guide/people)** - Users with access to the farmOS instance.
- **Administration** - Links to various administrative sections.

You can also use the map to navigate to records that relate to specific
locations. For example, if you want to view the record for a specific plant
Asset, you can navigate to the field in the map where the plant is located, and
you will be able to find all Assets in that field.

If you are looking for a specific Log, you can either find it via "Records >
Logs" in the toolbar, or you can find the Asset that the Log is associated with,
and the Log will appear there as well.

All your records can be connected and related in this way to make navigating
them easier.
