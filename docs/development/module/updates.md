# Automated updates

## Update hooks

farmOS modules may change and evolve over time. If these changes require
updates to a farmOS database or configuration, then update logic should be
provided so that users of the module can perform the necessary changes
automatically when they update to the new version.

This logic can be supplied via implementations of `hook_update_N()` and
`hook_post_update_NAME()`.

For more information, see the documentation for Drupal's
[Update API](https://www.drupal.org/docs/drupal-apis/update-api/).

## Configuration updates

If the farmOS Update module is enabled, changes to configuration entities will
be automatically reverted when caches are rebuilt. The purpose of this is to
make it easier for farmOS module developers to make minor changes to the
configuration included with their module without writing update hooks.

Note that this only handles overridden configuration. It does not handle
missing, inactive, or added configuration. It also does not touch "simple"
configuration (eg: module settings) - only configuration entities (eg: Views).

If your module is adding or deleting configuration, the recommended approach is
to implement `hook_post_update_NAME()` to perform the necessary operations.

In most cases this is desirable, but if you are intentionally overriding
configuration in your farmOS instance then you have a few options for
disabling this behavior.

### Disable farmOS Update module

The easiest way to disable automatic configuration updates is to turn off the
`farm_update` module. This can be done via Drush:

    drush pm-uninstall farm_update

This will completely disable automatic reverts of configuration. You can then
manage all configuration changes and deployment manually. One way to do this
is with the `config_update_ui` module, which provides a report of all missing,
inactive, added, and changed configuration. This can be enabled via Drush:

    drush en config_update_ui

Then go to `/admin/config/development/configuration/report/type/system.all` to
see the full report. Individual configuration items can be reverted, imported,
and deleted.

### Exclude specific configuration

Alternatively, if you want to keep automatic updates enabled, but want control
over certain items, the farmOS Update module provides two mechanisms for
excluding specific configuration from automatic updates.

#### `hook_farm_update_exclude_config()`

If a module overrides certain configuration items, either in
`hook_install()` or via something like the `config_rewrite` module, the
module can list these configuration items in an array returned by an
implementation of `hook_farm_update_exclude_config()`.

For example, in `mymodule.module`:

```php
/**
 * Implements hook_farm_update_exclude_config().
 */
function mymodule_farm_update_exclude_config() {

  // Exclude mymodule_custom view from automatic configuration updates.
  return [
    'views.view.mymodule_custom',
  ];
}
```

#### `farm_update.settings`

The farmOS Update module will also check the `exclude_config` setting in
its own `farm_update.settings` configuration for a list of configuration
items to exclude from automatic updates. This can be provided by a custom
module in `config/install/farm_update.settings.yml`, or synced/imported into
active configuration by other means.

For example, in `farm_update.settings.yml`:

```yaml
exclude_config:
  # Exclude mymodule_custom view from automatic configuration updates.
  - views.view.mymodule_custom
```
