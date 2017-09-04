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

## 3. Enter the key into the farmOS Map configuration

**a) If you are installing farmOS for the first time**, there is a field for entering
the Google Maps API key during the "Configure farmOS" step.

**b) If you have already installed farmOS**, you can enter the API key in the map
configuration form at `/admin/config/farm/map`.

**Note that according to Google it may take a couple of minutes before a new
API key becomes active.**

