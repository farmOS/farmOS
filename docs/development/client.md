# farmOS Client / Native app

Work has begun on a simplified farmOS app that works offline in both browser
and native app form. It will be installable on Android and iOS devices. The
goal is to create a fast and focused client app for day-to-day and in-the-field
record keeping that stores data locally for offline use, and syncs back to a
farmOS server when internet access is available.

The application is divided into two projects:

1. farmOS Client: [https://github.com/farmOS/farmOS-client]
2. farmOS Native: [https://github.com/farmOS/farmOS-native]

The "client" is where all of the UI and features are built. The "native"
project wraps that client inside a native app, which can be installed on
Android/iOS.

**This documentation is not complete. It is intended only for informational
purposes at this time.**

## Architecture
The client essentially represents a UI library; the native repository consumes that library, while also providing the client with the dependencies it needs to be able to run on native devices, via Cordova. Those dependencies are the data and login plugins, which perform data and authentication operations specific to the native environment. The client library is separated from the native implementation in this way is so that the client library can also be consumed by other, browser-based implementations, such as Drupal modules, or standalone single page applications.

### farmos-native
The native repo currently performs two main functions:
- housing the data and login plugins
- controlling the Cordova build process

The plugins are already quite self-contained. It seems like a forgone conclusion that at some point we'll want to make them into their own independent libraries. They're actually Vue plugins, which is all that's preventing all Vue-related dependencies (and devDependencies) from being removed from the native repo, so that time might need come sooner rather than later. Anticipating that fact, the data and login plugins are outlined separately below.

Putting the plugins aside for now then, the native repo really becomes quite a spare repository, basically comprised of the following:
- the configuration files and build scripts for npm, Webpack and Cordova
- a mostly empty `index.html`, which just serves as a target for the build scripts
- a `main.js` file, which only calls the client library and passes it the plugins as arguments

Being concerned solely with the final build process, it really doesn't contain much essential code of its own, only serving to bring all the necessary dependencies and scripts together in one place. Then all it has to do is call the client library's load function and pass in the dependencies it needs. It's conceivable that the whole thing could be replaced with a Makefile.

### data plugin
The data plugin supplies the client with methods for storing farmOS data on local disk with WebSQL, and for sending that data to a farmOS server via AJAX and the farmOS REST API.

Specifically, the data plugin is a [Vue plugin](https://vuejs.org/v2/guide/plugins.html), which implements
- Vuex subscribe methods, which 
  - listen for UI actions and mutations, then 
  - dispatch actions to the db and http modules;
- the db Vuex module, consisting of Vuex actions, which implement
  - WebSQL transactions, then
  - dispatches actions to the UI's store (eg, to update `logs`' "cached" status);
- the http Vuex module, consisting of Vuex actions, which implement
  - AJAX requests, then
  - dispatches actions to UI's store (eg, to update `logs`' "synced" status).
  
### login plugin
I need to finish the login plugin's documentation once I've gone through and figured out the authentication process better, and fixed some issues with the way it currently uses Vue mixins, but structurally it's pretty similar to the data plugin. 

The one crucial difference is that it also registers a Vue component, `Login.vue`, on the main Vue instance when it's installed by the client. There's a lot that's not ideal about the login plugin, but how it registers the Vue component could actually be a good model for how other components could be added dynamically to the client's core library, if we wanted to break up the UI itself into separate modules.

### farmos-client
Compared to the native repo, the client repo has a lot more going on. The primary organizing principle for all this, currently, is the Vue framework itself.

The load function, found in `src/app.js`, is the main entry point. It is basically a thin wrapper for instantiating the main Vue object, and installing any Vue plugins that were passed in as dependencies. It's only a few lines, so perhaps it's easiest to illustrate how it works by including it here in its entirety:

```js
export default (data, login) => {
  Vue.use(data, {store, router})
  if (typeof login !== 'undefined') {
    Vue.use(login, {router, store})
  }
  return new Vue({
    el: '#app',
    store,
    router,
    components: {App},
    template: '<App/>'
  })
};
```

Note that the login plugin is optional. Crucially, when the plugins are installed, they must be passed the Vuex store and Vue router. Also, the DOM selector `#app` is hardcoded here as the mount point for the Vue application. In the future, this selector should certainly be parameterized to allow more flexibility. 

Beyond the load function, the client can be summarized as comprising:

- Vue components, which implement
  - Rendering algorithms, based on current state of the Vuex store
  - Component methods, which dispatch actions/mutations to the store when DOM events are triggered
- The Vuex store, which implements 
  - a state tree, representing the entire UI state (eg, an array of log objects)
  - mutations, which transform the UI state (synchronously)
  - actions, which 
    - handle asynchronous requests from the data plugin and components, and 
    - 'commits' mutations to the store at different stages of those requests


## Development environments

See [farmOS/farmOS-native#19] for info on using npm-link in development.

### Browser

### Platform SDK's

* [Cordova docs on running the emulator and debugger in Android Studio]
* [Cordova docs on running the emulator and debugger in XCode]

[//]: <> (TODO: Add a few more details on this once I know more)

## Native build process

The process for building the native applications for iOS and Android can be
broken down into 2 main steps: first, Webpack bundles all the HTML, CSS and
JavaScript modules into their final form that will run in [WebView]; secondly,
Cordova, with some help from the Android and iOS SDK's (Android Studio & XCode,
respectively), builds the full-blown native packages (.apk and .ipa files,
respectively).

To build from source, you'll need to clone the farmOS-native repository from
GitHub:

```bash
$ git clone https://github.com/farmOS/farmOS-native.git
```

Note that the farmOS-client is a dependency which will be automatically
installed as an npm package in the following step, so you do not need to clone
that repo.

Bundling the web assets with Webpack is fairly straight-forward. [Node]
(v.6.0.0 or higher) and npm (v.3.0.0 or higher) are the only system
requirements. Once Node is installed, you can install the necessary JavaScript
dependencies with npm, which is automatically included with Node. The npm
script `build-mobile` can then run Webpack to bundle the assets. All this can
be done by running the following two commands from the project's root
directory:

```bash
$ npm install
$ npm run build-mobile
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

[//]: <> (TODO: Determine what versions of Android the app should target and list them here.)

Once Android Studio is installed and configured, make sure Android has been
added to Cordova's list of platforms, then you're ready to run the final build
command:

```bash
$ cordova platform add android
$ cordova build android
```

By default, the `build` command will produce a debugging APK (equivalent to
running `cordova build android --debug`). If you want to build a final release
version, you'll need to add the `--release` flag, but you'll also need to use
your keys for signing the APK (see "[Signing an App]" in the Cordova docs for
more details). Both the debug and release APK's can then be found at
`path/to/farmos-native/platforms/android/app/build/outputs/apk` after building.

[//]: <> (TODO: Figure out signing the app for the Play Store and document here.)

### iOS Build

Only available on Apple OS X. Windows and Linux are not supported. For some
workarounds, see "[Developing an iOS App on Linux]"

Cordova's [iOS Platform Guide]

[https://github.com/farmOS/farmOS-client]: https://github.com/farmOS/farmOS-client
[https://github.com/farmOS/farmOS-native]: https://github.com/farmOS/farmOS-native
[farmOS/farmOS-native#19]: https://github.com/farmOS/farmOS-native/issues/19#issuecomment-382198804
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

