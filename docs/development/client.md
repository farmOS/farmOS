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



## Development environment

...

## API requests

...

## Authentication

...

## Coding standards

...

