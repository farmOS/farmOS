# Importing data

[CSV] importers are provided for all [asset] and [log] types in farmOS.

Links to each importer can be found at the top of each primary asset or log
listing page (accessible via the [main menu] of farmOS). For example, if you
want to import Animal assets, click on Assets > Animals in the main menu, and
then click the "Import animals" action link at the top of the page.

There is a link to "Download a template" within the importer page, which will
give you a blank CSV file with all the necessary column headers. Start with the
template file, and add a row for each of the records you want to import. Save
this file and upload it to the importer form to create the new records in
farmOS.

## Common fields

Each asset/log type has its own importer, and some have fields that are unique
to their type, but there are some common fields that are shared across all
importers.

Common asset fields include:

* **Name** - The name of the asset (required).
* **Active** - Whether or not the asset is currently active. This should be set
  to "yes" or "1" if the asset is active, and "no" or "0" if the asset is not
  active. If omitted, this will default to "1" (active).
* **Description** - A longer description of the asset.
* **Parent IDs** - A comma-separated list of asset IDs that represent parents
  of the asset being imported. These parent assets must already exist in farmOS
  in order for the link to be created.

Common log fields include:

* **Name** - The name of the log. This will be automatically generated if it is
  left blank.
* **Date** - The date when the logged event takes place (required). This can be
  a string in any English date format that is convertable to a UNIX timestamp.
* **Done** - Whether or not the log is complete. This should be set to "yes" or "1"
  if the log is done, and "no" or "0" if the log is not done. If omitted, this
  will default to "1" (done).
* **Notes** - A longer description of the logged event.
* **Asset IDs** - A comma-separated list of asset IDs that this log is related
  to. These assets must already exist in farmOS in order for the link to be
  created.
* **Area names** - A comma-separated list of areas that this log is related to.
  Areas will be matched on their name, and new areas will be created if they do
  not exist.
* **Category names** - A comma-separated list of log categories that should be
  applied to the log. The categories must already exist in farmOS in order for
  the assignment to take place.

Common fields that are required are noted above. Specific asset/log type
importers may have additional required fields.

## Access

CSV importers are only available to users with the Farm Manager [role].

[CSV]: https://en.wikipedia.org/wiki/Comma-separated_values
[asset]: /guide/assets
[log]: /guide/logs
[main menu]: /guide#navigation
[role]: /guide/people

