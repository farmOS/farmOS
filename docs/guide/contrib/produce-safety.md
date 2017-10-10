# Produce Safety

[https://github.com/mstenta/farm_produce_safety]

The Produce Safety module for farmOS provides record keeping features for the
FSMA Produce Safety Rule in the United States, developed in partnership with
the [Agricultural Engineering Program] of [University of Vermont Extension]
with financial support from the [Vermont Agency of Agriculture Food & Markets]'
Specialty Crop Block Grant and from the [Vermont Housing & Conservation Board].

The [Produce Safety Rule] is part of the [Food Safety Modernization Act]
(FSMA). This regulation focuses on setting federal regulatory standards for the
production, harvest, and handling of fruits and vegetables, in an effort to
prevent microbial contamination and reduce foodborne illnesses associated with
fresh produce.

**This documentation should not be considered an official list of requirements.**

It is only intended to help farmers become familiar with using farmOS to manage
their records. For more information on guidelines, refer to the FDA's
[FSMA Final Rule on Produce Safety] and the [Produce Safety Alliance].

## Overview

The Produce Safety module sits on top of farmOS and utilizes many of the core
features, including Plantings, Equipment, Compost, and various log types. The
goal is to leverage the common record keeping capabilities that are already
provided by farmOS, and extend them to facilitate record keeping requirements
that are specific to the Produce Safety Rule.

The following documentation pages provide guidance on how to manage general
farm records with farmOS:

* [Introduction]
* [Areas]
* [Logs]
* [Assets]
    * [Plantings]
    * [Animals]
    * [Equipment]
    * [Compost]
* [People]

It may also be helpful to utilize the [CSV Import] feature of farmOS to import
records from spreadsheets.

The following sections describe farmOS features specific to the Produce Safety
module.

## Dashboard

The module provides a Produce Safety Dashboard (available as a tab within the
farmOS dashboard) which acts as an organized starting point for produce safety
record keeping. Documents and files that are specific to the operations food
safety plan and procedures can be uploaded to the dashboard for storage and
reference.

Quick links are provided for managing records within each of the 5 main produce
safety focus areas (described below).

Logs that are added via the quick links will automatically be assigned to the
"Produce Safety" category for easier lookup in the future (as well as additional
categories that may be specific to the activity being recorded). Remember that
farmOS can be used for more than just Produce Safety record keeping, so it is
up to you to keep your records organized in a way that makes them easy to find
for management and reporting purposes.

## Focus areas

The primary goal of the Produce Safety Rule is to prevent microbial
contamination and reduce foodborne illnesses associated with fresh produce. The
regulatory requirements are divided into five main focus areas.

### Worker Health, Hygiene, and Training

This focuses on maintaining records to demonstrate that farm workers are
properly trained, are provided with stocked and sanitary facilities, and any
health problems are properly documented.

In addition to the core log types provided by farmOS, the Produce Safety module
adds two that are specific to the Produce Safety Rule's record keeping
requirements, which focus on farm workers:

* **Training** logs are used to record details about training sessions that are
  attended by workers on the farm. Training logs can include a list of
  attendees, trainer(s), and details about the material that is covered.
* **Worker health** logs are used to record incidents related to worker health.
  Injuries or illnesses that occur on the farm should be recorded with these
  logs, and should be related to the specific area(s) that they may have
  occurred in.

Activity and/or observation logs should be used to record checking and
restocking of first aid and facilities.

### Biological Soil Amendments

All soil amendments should be recorded with input logs. Soil test logs should
be used to keep track of any lab tests that are performed.

If compost is being produced on the farm, it is necessary to keep records of
production time, temperature measurements (via observation logs), and pile
turnings (via activity logs), to ensure that the compost was produced in a
manner that reduces the risk of biological pathogens. Logs should be tagged
with both the "Produce Safety" and "Compost" or "Soil" categories, as
appropriate.

### Domesticated and Wild Animals

If domesticated animals are present on the farm, they should be managed as
[Animal assets] in farmOS.

Risk assessment should be performed (and recorded via observation logs) before
planting and before harvest, to reduce the risk of contamination.

If intrusions or contaminations are observed, they should be recorded as
observation logs. Corrective actions should be recorded as activity logs. Logs
should be tagged with both the "Produce Safety" and "Animals" categories.

### Agricultural Water

Water test logs should be used to record lab tests that are performed on water.
This includes both field water (pre-harvest) and water that is used to wash
produce before packing (post-harvest).

Any corrective actions that are taken should be recorded as activity logs with
categories of both "Produce Safety" and "Water" (these categories are applied
automatically by the quick links within the Produce Safety dashboard).

### Equipment, Tools, and Buildings

All areas relevant to produce safety (fields, buildings, facilities, etc) can
be managed as [Areas] in farmOS. Tools and equipment can be managed as
[Equipment] assets.

When areas or equipment are cleaned/sanitized, this should be recorded as an
activity log with the "Produce Safety" category applied.

[https://github.com/mstenta/farm_produce_safety]: https://github.com/mstenta/farm_produce_safety
[Agricultural Engineering Program]: https://www.uvm.edu/extension/agriculture/agricultural_engineering
[University of Vermont Extension]: https://www.uvm.edu/extension
[Vermont Agency of Agriculture Food & Markets]: http://agriculture.vermont.gov
[Vermont Housing & Conservation Board]: http://www.vhcb.org
[Produce Safety Rule]: https://www.fda.gov/Food/GuidanceRegulation/FSMA/ucm334114.htm
[Food Safety Modernization Act]: https://www.fda.gov/food/guidanceregulation/fsma
[FSMA Final Rule on Produce Safety]: https://www.fda.gov/Food/GuidanceRegulation/FSMA/ucm334114.htm
[Produce Safety Alliance]: https://producesafetyalliance.cornell.edu
[Introduction]: /guide
[Areas]: /guide/areas
[Logs]: /guide/logs
[Assets]: /guide/assets
[Plantings]: /guide/assets/plantings
[Animals]: /guide/assets/animals
[Equipment]: /guide/assets/equipment
[Compost]: /guide/assets/compost
[People]: /guide/people
[CSV Import]: /guide/import
[Animal assets]: /guide/assets/animals

