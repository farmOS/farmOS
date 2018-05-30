# farmOS Client / Native app

...

## Architecture

Here's a basic overview of how the client and native repos make use of the Vue ecosystem and its API's:

- The client implements
  - Vue components, which implement
    - Rendering algorithms, based on current state of the Vuex store
    - Component methods, which dispatch actions/mutations to the store when DOM events are triggered
  - The Vuex store, which implements 
    - a state tree, representing the entire UI state (eg, an array of log objects)
    - mutations, which transform the UI state (synchronously)
    - actions, which 
      - handle asynchronous requests from the data plugin and components, and 
      - 'commits' mutations to the store at different stages of those requests
- The data plugin (a Vue plugin) implements
  - Vuex subscribe methods, which 
    - listen for UI actions and mutations, then 
    - dispatch actions to the db and http modules
  - The db module, consisting of Vuex actions which implement
    - WebSQL transactions, then
    - dispatches actions to the UI's store (eg, to update `logs`' "cached" status)
  - The http module, consisting of Vuex actions which implement:
    - AJAX requests, then
    - dispatches actions to UI's store (eg, to update `logs`' "synced" status)



## Development environments

### Browser

### Platform SDK's
- [Cordova docs on running the emulator and debugger in Android Studio](https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#debugging)
- [Cordova docs on running the emulator and debugger in XCode](https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html#debugging)
[//]: <> (TODO: Add a few more details on this once I know more)

...

## Native build process
The process for building the native applications for iOS and Android can be broken down into 2 main steps: first, Webpack bundles all the HTML, CSS and JavaScript modules into their final form, optimized for [WebView](https://cordova.apache.org/docs/en/latest/guide/hybrid/webviews/); secondly, Cordova, with some help from the Android and iOS SDK's (Android Studio & XCode, respectively), builds the full-blown native packages (.apk and .ipa files, respectively).

Bundling the web assets with Webpack is fairly straight-forward. [Node](https://nodejs.org) (v.6.0.0 or higher) and npm (v.3.0.0 or higher) are the only system requirements. Once Node is installed, you can install the necessary JavaScript dependencies with npm, which is automatically included with Node. The npm script `build-mobile` can then run Webpack to bundle the assets. All this can be done by running the following two commands from the project's root directory:

```bash 
$ npm install
$ npm run build-mobile
```

This will generate all the necessary files within the `www` directory, which Webpack will create if it doesn't already exist. Do not alter these files directly. They are optimized for WebView, which is basically a browser that Cordova installs and runs inside the native application itself to render all the HTML, CSS and JavaScript source files. The same files are used by both iOS and Android implementations

Everything is now ready for building the final native packages. Both platforms will require installing Cordova globally, via npm:

```bash 
$ npm install -g cordova
```

This, however, is where the process diverges into separate iOS and Android builds. This stage will probably comprise the most system configuration, too, since it requires installing each platform's SDK (Software Development Kit), if they aren't installed already. Of course, if you only intend to build for one platform, you only need to install that platform's SDK and corresponding system requirements; building for both platforms will require installing both SDK's.

### Android Build 
To configure your system to build the Android app, follow Cordova's ["Android Platform Guide"](https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html) and the [Android Studio installation guide](https://developer.android.com/studio/install). This will differ depending on your development machine's operating system, but Mac, Windows and Linux are all supported.

**System Requirements:**

- Java 8 
- Android Studio
- Gradle

Note that Android Studio recommends the official Oracle JDK for Java 8; using OpenJDK may cause errors. Also make sure to follow Cordova's instructions for setting up your system's [environment variables](https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#setting-environment-variables) and installing the [SDK packages](https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#adding-sdk-packages) for the Android versions the app will target.

[//]: <> (TODO: Determine what versions of Android the app should target and list them here.)

Once Android Studio is installed and configured, run the `prepare` command so that Cordova loads the settings from `config.xml` and creates the necessary folders and manifest files, and assets for Android. Then you're ready to run the final build command: 

```bash
$ cordova prepare android
$ cordova build android
```

By default, the `build` command will produce a debugging APK (the same as running `cordova build android --debug`). If you want to build a final release version, you'll need to add the `--release` flag, but you'll also need to use your keys for signing the APK (see ["Signing an App"](https://cordova.apache.org/docs/en/latest/guide/platforms/android/index.html#signing-an-app) in the Cordova docs for more details). 

The .apk can then be found at `path/to/farmos-native//platforms/android/app/build/outputs/apk`

[//]: <> (TODO: Figure out signing the app for the Play Store and document here.)

### iOS Build 
Only available on Apple OS X. Windows and Linux are not supported. For some workarounds, see ["Developing an iOS App on Linux"](https://andrewmichaelsmith.com/2017/02/developing-an-ios-app-on-linux-in-2017/)

Cordova's [iOS Platform Guide](https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.htm)


## API requests

...

## Authentication

...

## Coding standards

...

