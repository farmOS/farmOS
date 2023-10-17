# Importing data

## CSV

[CSV](https://en.wikipedia.org/wiki/Comma-separated_values) files are an easy
way to import data into farmOS.

The *CSV Import* module adds simple importers for each Asset type, Log type,
and Taxonomy term type provided by farmOS modules. Template CSV files can be
downloaded, filled in, and re-uploaded to create new records in the system.
You can also see a list of all records that were created by each importer,
and easily review/modify/delete them after import.

farmOS also provides a framework for developing more specialized importers with
custom CSV column headers. See [CSV importers](/development/module/csv) in the
farmOS Development Guide for more information.

## Geometry

Geometry data can be imported into Assets and Logs in a few different ways.

Well-known text (WKT) can be pasted into any geometry field when you are
creating or editing individual Assets and Logs. These fields also support
GeoJSON and other formats.

farmOS can also import geometries from KML, KMZ, GPX, and GeoJSON files. If you
already have your farm mapped in another software (like Google Earth), you can
export KML/KMZ files for each area and then import them into farmOS records.
Alternatively, you can use the *KML Importer* module to import a single KML
file that contains the shapes for multiple Land Assets.

To import a KML, KMZ, GPX, or GeoJSON file to an individual Asset or Log,
follow these steps:

1. Create a new Asset or Log (or edit an existing one).
2. Scroll down to the Files field and upload your KML file.
3. Scroll to the Geometry field, and just below the map you will see a button
   labeled "Import geometry from uploaded files". If you uploaded a valid file,
   you will see the shape(s) appear in the map.

To import a KML/KMZ file containing multiple Land Assets, follow these steps:

1. Enable the *KML Importer* module.
2. Go to Administration > Import > KML Import in the toolbar.
3. Upload your KML/KMZ file, select the default land type to assign to new
   Assets, and click "Parse".
4. If geometries are found in the file, a fieldset will be shown for each of
   them, letting you customize the Asset name and land type, or exclude it from
   import.
5. At the bottom of the form, there is an option to create a new Asset that
   will contain all the imported Assets. This is helpful so that you can easily
   review the Assets after they are imported. If you find that the import
   didn't work properly, you can select all the Assets under this parent Asset
   and delete them together.
