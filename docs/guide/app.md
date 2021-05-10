# farmOS Field Kit

farmOS Field Kit is a way to connect to your farmOS server from your mobile
device. Because much of the work of an active farm can occur outside of wifi or
cellular range, Field Kit is designed to be an offline-first app. You can create
and modify farm logs wherever you're at, whenever you need to; then, when you
are back within signal range, you can synchronize all your logs with your farmOS
server.

Field Kit provides a simplified view of the farm's records, working much like a
to-do app. Logs of upcoming farm activities that have been assigned to you will
be synced to your device. As you complete each activity, you can update the log
with real-time data and photos, mark it as done, then sync it back to the
server. You can also create new logs, such as observations or other log types,
all on the fly.

## Releases

An early version of Field Kit is available as a web app, which can be found at
[v1.farmos.app] and can be installed as a [Progressive Web App] (PWA). As a PWA,
the app will work offline and your logs will be stored in between uses. You can
even add the app to your device's home screen, so it can be launched just like a
native app.

Version 1 is a proof-of-concept, which should be considered relatively stable,
but which will no longer receive major feature updates beyond a few bug fixes.
In conjunction with farmOS 2.0, we are in active development of Field Kit 2.0,
which will bring with it new features and its own module system, akin to farmOS
modules. Stay tuned for more!

Previously, and perhaps again in the future, Field Kit was available as native
apps for Android and iOS. The Android app can still be installed from the
[Play Store], but is currently not receiving regular updates. The iOS app was
previously available on [Test Flight], but at the time of writing this (Apr 2021)
all builds have expired and are no longer available for download; previous
installs will not be updated for the foreseeable future. We encourage all users
to migrate to the PWA, which shares the same features as the native apps. For
more details on this decision, see the related [forum post].

## Server requirements

All versions of Field Kit require that your farmOS server have an SSL certificate,
which enables network traffic to be served via `https` rather than `http`. Also,
make sure that [clean URL's] are enabled. Finally, you should keep your server
updated with the most recent version of farmOS to ensure compatibility with the
the latest version of Field Kit.

## Development

For technical information on the project, please see the [Development section].

The source code can be viewed on [GitHub]


[Play Store]: https://play.google.com/store/apps/details?id=org.farmos.app
[Test Flight]: https://developer.apple.com/testflight/
[forum post]: https://farmos.discourse.group/t/field-kit-a-platform-dilemma/433
[v1.farmos.app]: https://v1.farmos.app
[Progressive Web App]: https://developers.google.com/web/progressive-web-apps/
[clean URL's]: https://www.drupal.org/docs/7/configuring-clean-urls/enable-clean-urls
[Development Section]: /development/client
[GitHub]: https://github.com/farmOS/farmOS-client
