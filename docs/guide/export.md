# Exporting data

All [asset] and [log] lists in farmOS provide a [CSV] export button at the
bottom that will generate and download a CSV file.

Any sorts or filters that are applied to the list will be represented in the
CSV output.

## Reimporting

**While it is technically possible to move data from one farmOS to another via
CSV files, it is not recommended due to differences in the import/export CSV
format.**

If you are trying to export assets or logs from one farmOS site so that they
can be imported into another farmOS site it is important to note that there are
some differences that can complicate things. It is technically possible, but
there are some limitations and differences to be aware of. Please read all of
the following before attempting it.

There is an open issue to resolve these differences here:
[https://www.drupal.org/node/2900239](https://www.drupal.org/node/2900239)

### Differences and considerations

Some of the differences and considerations to be aware of are described below.

#### Column differences

In most cases, the CSV column names that are exported from asset and log lists
will match those of the corresponding [CSV importer] for that type. There may
be columns present in imports that are not present in exports, and vice versa.
Compare the exported CSV columns to the importer's CSV template columns before
importing to understand what pieces of information might be missing from either
side.

#### Asset and log IDs

Exported CSVs will include a column for the asset or log ID, which is not
available as a field for import. You can still import CSVs with this column,
but it will be ignored during the import and a new ID will be assigned by
farmOS to the imported asset or log. If there are any other logs or assets that
reference this ID, they will need to be manually updated to point to the
correct IDs when you import them.

#### Log "Done" column

In log exports, the "Done" column will contain a checkmark if the log is done,
and it will be empty if the log is not done. This differs from the format
expected by log importers. Log importers expect a value of "yes" or "no" in the
"Done" column, and blank values will automatically default to "yes", which is
the opposite of what a blank value means in CSV exports.

#### Truncated text

Descriptions, notes, and other long text fields are truncated when they are
displayed in asset and log lists in farmOS. When those lists are exported to
CSV, the text will also be truncated in the export.

#### Files and images

CSV exports do not provide any mechanism for exporting images or files that are
attached to assets or logs. Files and images need to be uploaded manually after
import.

#### Asset location

The CSV importers provided for assets do not currently support setting asset
location, and log importers do not currently support importing movement
information. Asset location needs to be set manually after assets are imported.

[asset]: /guide/assets
[log]: /guide/logs
[CSV]: https://en.wikipedia.org/wiki/Comma-separated_values
[CSV importer]: /guide/import

