# farmOS Client

Work has begun on a simplified farmOS app that works offline in both browser
and native app form. It is installable on Android and iOS devices. The
goal is to create a fast and focused client app for day-to-day and in-the-field
record keeping that stores data locally for offline use, and syncs back to a
farmOS server when internet access is available.

The application consists of the main client, which provides the UI and core
features, as well as native plugins, which provide specific support for native
functionality, like the ability to persist data and access the camera.

The source code can be found here: [https://github.com/farmOS/farmOS-client]

**This documentation is not complete. It is intended only for informational
purposes at this time.**

## User's Guide

Please see the [User's Guide] for more information on how to install and use
the client on your devices.

## Architecture
The client is built with [Vue.js] and [Apache Cordova]. Initially, the client 
and its native implementation were maintained as separate libraries. This was 
done with the aim of being able to isolate native functionality in [Vue plugins] 
that could be swapped out for browser-based plugins. This may not be strictly 
necessary in the future, but it does at least make for good separation of 
concerns.

## Development environments
The first thing of course is to clone the client repo from GitHub, and install
the npm dependencies:

```bash
$ git clone https://github.com/farmOS/farmOS-client.git
$ cd farmOS-client
$ npm install
```

Note that the default branch for this repository is `develop`, not `master`;
`develop` should represent the most current set of complete features that
are only awaiting further testing before release. You should branch or fork
off `develop` and submit pull requests to be merged back into it. The 
`deploy` branch respresents the latest tagged release.

### Browser
The browser-based development environment can be started by running the
following command from the project root:

```bash
$ npm start
```

This will start the Webpack devServer, which will compile a development build
and serve it from [http://localhost:8080/]. Changes saved to the files in your
local repo will be hot reloaded automatically while the devServer is running.

#### Proxying a farmOS Docker container
By default, when the Webpack devServer starts, it will set up a proxy service,
which will route all AJAX requests and responses through `http://localhost:80`,
in order to prevent CORS errors. This is assumed to be the address of a local
farmOS development server running in a Docker container. This is probably the
easiest way to get a development backend up and running, so if you don't already
have a sever you can use for testing, see the [full instructions for setting up
a farmOS Docker container].

If you wish to proxy an address other than `http://localhost:80`, you'll need to
change the proxy settings in `farmOS-native/config/index.js`. Set
`dev.proxy.target` to the address and port of your farmOS testing server.
Restart the devServer and it should now be proxying your new address. It may
also be necessary to add new endpoints to the `dev.proxy.context` array as new
features are developed and new endpoints on the farmOS server need to be
reached. For more information, see the [Webpack documentation on configuring the
proxy middleware].

You will also need to configure the server to enable CORS requests. Go to
[http://localhost/admin/config/farm/access] and enter `http://localhost:8080`
as the Access-Control-Allow-Origin.

Finally, when logging in to the client from the browser, simply leave the URL
field blank. The devServer will then interpret all requests as relative links
and proxy them accordingly. For some reason login can sometimes fail on the
first attempt, but should succeed on all subsequent attempts.

### Platform SDK's

* [Cordova docs on running the emulator and debugger in Android Studio]
* [Cordova docs on running the emulator and debugger in XCode]

[//]: <> (TODO: Add a few more details on this once I know more)

## Web build process

To build the client for the web, you just need to run the build script:

```bash
$ npm run build:web
```

The output of this script will be found in the `dist` directory and can be
deployed using web server.

## Native build process

The process for building the native applications for iOS and Android can be
broken down into 2 main steps: first, Webpack bundles all the HTML, CSS and
JavaScript modules into their final form that will run in [WebView]; secondly,
Cordova, with some help from the Android and iOS SDK's (Android Studio & XCode,
respectively), builds the full-blown native packages (.apk and .ipa files,
respectively).

To build from source, you'll need to clone the client repository from
GitHub:

```bash
$ git clone https://github.com/farmOS/farmOS-client.git
```

Bundling the web assets with Webpack is fairly straight-forward. [Node]
(v.6.0.0 or higher) and npm (v.3.0.0 or higher) are the only system
requirements. Once Node is installed, you can install the necessary JavaScript
dependencies with npm, which is automatically included with Node. The npm
script `build:native` can then run Webpack to bundle the assets. All this can
be done by running the following two commands from the project's root
directory:

```bash
$ npm install
$ npm run build:native
```

This will generate all the necessary files within the `www` directory, which
Webpack will create if it doesn't already exist. Do not alter these files
directly. They are optimized for WebView, which is basically a browser that
Cordova installs and runs inside the native application itself to render all
the HTML, CSS and JavaScript source files. The same files are used by both iOS
and Android implementations

All the web assets are now ready for building the final native packages. Both
platforms will require installing Cordova globally, via npm:

```bash
$ npm install -g cordova
```

This, however, is where the process diverges into separate iOS and Android
builds. This next stage will probably comprise the most system configuration,
too, since it requires installing each platform's SDK (Software Development
Kit), if they aren't installed already. Of course, if you only intend to build
for one platform, you only need to install that platform's SDK and
corresponding system requirements; building for both platforms will require
installing both SDK's.

### Android Build

To configure your system to build the Android app, follow Cordova's
[Android Platform Guide]" and the [Android Studio installation guide]. This
will differ depending on your development machine's operating system, but Mac,
Windows and Linux are all supported.

**System Requirements:**

- Java 8
- Android Studio
- Gradle

Note that Android Studio recommends the official Oracle JDK for Java 8; using
OpenJDK may cause errors. Also make sure to follow Cordova's instructions for
setting up your system's [environment variables] and installing the
[SDK packages] for the Android versions the app will target. As of May 2018,
the latest version of Android that Cordova seems to support is 7.1.1, at API
Level 25.

Once Android Studio is installed and configured, make sure Android has been
added to Cordova's list of platforms, then you're try out the build command:

```bash
$ cordova platform add android
$ cordova build android
```

By default, the `build` command will produce a debugging APK (equivalent to
running `cordova build android --debug`). If you want to build a final release
version, you'll need to add the `--release` flag, but you'll also need to use
your keys for signing the APK (see "[Signing an App]" in the Cordova docs for
more details). For reference, the official farmOS Field Kit app is built using
the following flags:

```bash
cordova build android --release -- \
--keystore=/path/to//your-key.keystore \
--storePassword=xxxxxxxx \
--alias=yyyy --password=zzzzzzzz
```

Both the debug and release APK's can then be found at
`path/to/farmOS-client/platforms/android/app/build/outputs/apk` after building.

### iOS Build

Only available on Apple OS X. Windows and Linux are not supported. For some
workarounds, see "[Developing an iOS App on Linux]"

You'll need Xcode installed in order to run the Cordova build commands. See
Cordova's [iOS Platform Guide] for more info.

Once you have the environment set up, you'll want to add the iOS platform with
Cordova (we recommend version 5.0.0 or higher), then you can run a debugging
build:

```bash
cordova platform add ios@5.0.0
cordova build ios
```

Using the `build` command without the `--release` flag triggers a debug build
by default. To build a signed app for the App Store, you'll need to follow
Cordova's [app signing guidelines], but for reference, we use the following
command and flags:

```bash
cordova build ios --release --device \
--codeSignIdentity="iPhone Distribution" \
--developmentTeam="ABCD1234" \
--packageType="app-store" \
--provisioningProfile="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
```

Signed .ipa files will be found at `path/to/farmOS-client/platforms/ios/build/device/`

[https://github.com/farmOS/farmOS-client]: https://github.com/farmOS/farmOS-client
[https://github.com/farmOS/farmOS-native]: https://github.com/farmOS/farmOS-native
[User's Guide]: /guide/app
[Vue.js]: https://vuejs.org/
[Apache Cordova]: https://cordova.apache.org/
[Vue plugins]: https://vuejs.org/v2/guide/plugins.html
[http://localhost:8080/]: http://localhost:8080/
[full instructions for setting up a farmOS Docker container]: /development/docker/
[Webpack documentation on configuring the proxy middleware]: https://webpack.js.org/configuration/dev-server/#devserver-proxy
[http://localhost/admin/config/farm/access]: http://localhost/admin/config/farm/access
[Cordova docs on running the emulator and debugger in Android Studio]: https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#debugging
[Cordova docs on running the emulator and debugger in XCode]: https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html#debugging
[WebView]: https://cordova.apache.org/docs/en/latest/guide/hybrid/webviews/
[Node]: https://nodejs.org
[Android Platform Guide]: https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html
[Android Studio installation guide]: https://developer.android.com/studio/install
[environment variables]: https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#setting-environment-variables
[SDK packages]: https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#adding-sdk-packages
[Signing an App]: https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#signing-an-app
[Developing an iOS App on Linux]: https://andrewmichaelsmith.com/2017/02/developing-an-ios-app-on-linux-in-2017/
[iOS Platform Guide]: https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html
[app signing guidelines]: https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html#signing-an-app
