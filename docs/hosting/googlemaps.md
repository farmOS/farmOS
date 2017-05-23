# Google Maps API Key

As of June 22nd, 2016 the Google Maps API requires an API key.

This means that if you are hosting farmOS yourself, you need to create an API
key in order to use any of the mapping components in farmOS.

Anyone who had a farmOS site installed before June 22nd, 2016, and was actively
using it, was automatically grandfathered in and does not need an API key:

> Existing applications have been grandfathered based on their current usage to
> ensure that they continue to function both now and in the future. We will
> also be proactively contacting all existing API key users who, based on usage
> growth patterns, may be impacted in the future. If you’re an existing user,
> please take the time to read our Policy Update for Standard Plan summary for
> details on how each of these changes might affect your implementation.

[https://maps-apis.googleblog.com/2016/06/building-for-scale-updates-to-google.html](https://maps-apis.googleblog.com/2016/06/building-for-scale-updates-to-google.html)

To create an API key and add it to your farmOS, do the following:

## 1. Generate a Browser Key

[https://console.developers.google.com/apis/credentials](https://console.developers.google.com/apis/credentials)

## 2. Enable Google Maps Javascript API

[https://console.developers.google.com/apis/api/maps_backend/overview](https://console.developers.google.com/apis/api/maps_backend/overview)

## 3. Enable the Openlayers UI module

In your farmOS site, log in as user 1 (the administrative user that you created
when you installed farmOS) and go to: `/admin/modules`

Find the "Openlayers UI" module and enable it.

## 4. Enter the key into the Google Maps Hybrid source in Openlayers

In your farmOS site, go to: `/admin/structure/openlayers/sources/list/farm_map_source_google_hybrid/edit/options`

Copy and paste your API key into the "Key" field and click Save.

**Note that according to Google it may take a couple of minutes before a new
API key becomes active.**

