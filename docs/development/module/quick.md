# Quick forms

Quick forms provide a simplified user interface for common data entry tasks.

## Building quick forms

To add a quick form to a module, create a quick form plugin class in
`src/Plugin/QuickForm` that extends the `QuickFormBase` class, and add a
dependency on `farm:farm_quick` to the module's `*.info.yml` file.

Quick forms are essentially just specialized forms created using Drupal's
[Form API](https://www.drupal.org/docs/drupal-apis/form-api/introduction-to-form-api),
with some special wrappers and helper methods to simplify and standardize
common requirements in the context of farmOS. They are defined as plugins via
a single PHP class. farmOS handles all the rest, including adding them to the
main navigation menu.

For example, a simple "Harvest" quick form can be provided in a module
comprised of two files (the `*.info.yml` file and the quick form plugin class),
as follows:

`/farm_quick_harvest.info.yml`

```yaml
name: Harvest Quick Form
description: Provides a quick form for recording a harvest.
type: module
package: farmOS Quick Forms
core_version_requirement: ^10
dependencies:
  - farm:farm_harvest
  - farm:farm_quantity_standard
  - farm:farm_quick
```

This file defines the module itself, along with the dependencies required by
this quick form. In this example, the "Harvest" (`farm:farm_harvest`) and
"Standard quantity" (`farm:farm_quick_standard`) modules are dependencies, in
addition to the "Quick form" module (`farm:farm_quick`).

`/src/Plugin/QuickForm/Harvest.php`:

```php
<?php

namespace Drupal\farm_quick_harvest\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;

/**
 * Harvest quick form.
 *
 * @QuickForm(
 *   id = "harvest",
 *   label = @Translation("Harvest"),
 *   description = @Translation("Record when a harvest takes place."),
 *   helpText = @Translation("Use this form to record a harvest."),
 *   permissions = {
 *     "create harvest log",
 *   }
 * )
 */
class Harvest extends QuickFormBase {

  use QuickLogTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Date+time selection field (defaults to now).
    $form['timestamp'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('now', \Drupal::currentUser()->getTimeZone()),
      '#required' => TRUE,
    ];

    // Asset reference field (allow multiple).
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assets'),
      '#target_type' => 'asset',
      '#tags' => TRUE,
    ];

    // Harvest quantity field.
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Draft a harvest log from the user-submitted data.
    $timestamp = $form_state->getValue('timestamp')->getTimestamp();
    $asset = $form_state->getValue('asset');
    $quantity = $form_state->getValue('quantity');
    $log = [
      'type' => 'harvest',
      'timestamp' => $timestamp,
      'asset' => $asset,
      'quantity' => [
        [
          'type' => 'standard',
          'value' => $quantity,
        ],
      ],
      'status' => 'done',
    ];

    // Create the log.
    $this->createLog($log);
  }

}
```

This file declares a new `Harvest` class, that extends from the `QuickFormBase`
class.

The `buildForm()` method builds the quick form, by adding "Date", "Asset", and
"Quantity" fields. A "Submit" button will be automatically added by the base
class, but can be overridden if customization is required.

The `submitForm()` is responsible for gathering the input and saving it to a
harvest log. It uses the `createLog()` helper method that is provided by the
`QuickLogTrait` trait, and the `entityLabelsSummary()` method provided by the
`QuickStringTrait` trait to build a log name.

See [Methods](#methods) and [Traits](#traits) below for more information about
the available methods, or examine the `QuickFormBase` class to understand the
internal workings.

### Annotation

The `@QuickForm` annotation comment above the class declaration is required,
and provides import metadata about the quick form.

- `id` - The quick form's unique ID.
- `label` - The translated label of the quick form displayed at the top of the
  quick form and in the quick form index page.
- `description` - The translated description of the quick form displayed in the
  quick form index page.
- `helpText` - The translated help text of the quick form displayed above the
  quick form when the Help module is enabled.
- `permissions` - An array of permissions that are required to access the quick
  form.

### Methods

The `QuickFormBase` class implements all the necessary methods defined in the
`QuickFormInterface` interface, and child classes can choose to override only
the ones they need to. At minimum, this will usually include the `buildForm()`
and `submitForm()` methods.

Available methods include:

- `access()` - Checks to see if the current use has access to the quick form.
  If omitted, then the `QuickFormBase::access()` parent method will check to
  see if the user has all of the permissions specified in the list of
  `permissions` in the `@QuickForm` annotation. Overriding this method allows
  a quick form to implement more customized access control logic.
- `buildForm()` - Build the quick form as an array using the
  [Drupal Form API](https://www.drupal.org/docs/drupal-apis/form-api/introduction-to-form-api).
- `validateForm()` - Perform validation on the user input.
- `submitForm()` - Perform logic when the form is submitted. This will not run
  if validation fails.

### Traits

farmOS provides some helpers for common quick form operations. These are
available in the form of traits that can be added to the quick form class.
Available traits and the methods that they provide include:

- `QuickAssetTrait`
  - `createAsset($values)` - Creates and returns a new asset entity from
    an array of values. This also creates a link in the database between the
    entity and the quick form that created it, and displays a message to the
    user upon submission with a link to the entity.
- `QuickLogTrait`
  - `createLog($values)` - Creates and returns a new log entity from an
    array of values. This also creates a link in the database between the
    entity and the quick form that created it, and displays a message to the
    user upon submission with a link to the entity.
- `QuickPrepopulateTrait`
  - `getPrepopulatedEntities($entity_type)` - Returns entities of the specified
    entity type that have been prepopulated for the quick form. Entities may
    be prepopulated by either a query param or a user specific tempstore that
    is populated by the quick form action.
- `QuickQuantityTrait`
  - `createQuantity($values)` - Creates and returns a new quantity entity from
    an array of values.
- `QuickStringTrait`
  - `trimString($value, $max_length, $suffix)` - Trims a string down to the
    specified length, respecting word boundaries.
  - `prioritizedString($strings, $priority_keys, $max_length, $suffix)` -
    Concatenates strings together with some intelligence for prioritizing
    certain parts when the full string will not fit within a maximum length.
    Expects a keyed array of strings to concatenate together, along with an
    optional array of keys that should be prioritized in case the full string
    won't fit.
  - `entityLabelsSummary($entities, $cutoff)` - Generate a summary of entity
    labels. Example: "Asset 1, Asset 2, Asset 3 (+ 15 more)". Note that this
    does NOT sanitize the entity labels. It is the responsibility of downstream
    code to do so, if it is printing text to the page.
- `QuickTermTrait`
  - `createTerm($values)` - Creates and returns a new term entity from an array
    of values.
  - `createOrLoadTerm($name, $vocabulary)` - Attempts to load an existing term,
    given a name and vocabulary. If the term does not exist, a new term will be
    created.

## Configurable quick forms

A "configurable" quick form is one that allows users to change how the quick
form behaves, by providing settings and a configuration form for customizing
them.

To make an existing quick form configurable:

1. Add `implements ConfigurableQuickFormInterface` to the quick form's class
   definition. This indicates to farmOS that the quick form is configurable,
   builds a router item for the configuration form, adds it to the UI, etc.
2. Add `use ConfigurableQuickFormTrait` to the quick form's class definition.
   This adds default methods required by the `ConfigurableQuickFormInterface`.
3. Add a `defaultConfiguration()` method that returns an array of default
   configuration values.
4. Add a `buildConfigurationForm()` method that builds a configuration form
   with form items for each of the properties defined in
   `defaultConfiguration()`.
5. Add a `submitConfigurationForm()` method that processes submitted values and
   assigns configuration to `$this->configuration`.
6. Add a `config/schema/[mymodule].schema.yml` file that describes the
   [configuration schema/metatdata](https://www.drupal.org/docs/drupal-apis/configuration-api/configuration-schemametadata).
7. Add `'#default_value' => $this->configuration['...']` lines to the form
   elements that are configurable in the `buildForm()` method.

The following is the same "Harvest" example as above, with the changes described
above, followed by the schema file that describes the settings.

```php
<?php

namespace Drupal\farm_quick_harvest\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\ConfigurableQuickFormInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\ConfigurableQuickFormTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;

/**
 * Harvest quick form.
 *
 * @QuickForm(
 *   id = "harvest",
 *   label = @Translation("Harvest"),
 *   description = @Translation("Record when a harvest takes place."),
 *   helpText = @Translation("Use this form to record a harvest."),
 *   permissions = {
 *     "create harvest log",
 *   }
 * )
 */
class Harvest extends QuickFormBase implements ConfigurableQuickFormInterface {

  use ConfigurableQuickFormTrait;
  use QuickLogTrait;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'default_quantity' => 100,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Date+time selection field (defaults to now).
    $form['timestamp'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('now', \Drupal::currentUser()->getTimeZone()),
      '#required' => TRUE,
    ];

    // Asset reference field (allow multiple).
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assets'),
      '#target_type' => 'asset',
      '#tags' => TRUE,
    ];

    // Harvest quantity field.
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['default_quantity'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Draft a harvest log from the user-submitted data.
    $timestamp = $form_state->getValue('timestamp')->getTimestamp();
    $asset = $form_state->getValue('asset');
    $quantity = $form_state->getValue('quantity');
    $log = [
      'type' => 'harvest',
      'timestamp' => $timestamp,
      'asset' => $asset,
      'quantity' => [
        [
          'type' => 'standard',
          'value' => $quantity,
        ],
      ],
      'status' => 'done',
    ];

    // Create the log.
    $this->createLog($log);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    // Default quantity configuration.
    $form['default_quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Default quantity'),
      '#default_value' => $this->configuration['default_quantity'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['default_quantity'] = $form_state->getValue('default_quantity');
  }

}
```

`config/schema/farm_quick_harvest.schema.yml`:

```yaml
farm_quick.settings.harvest:
  type: quick_form_settings
  label: 'Harvest quick form settings'
  mapping:
    default_quantity:
      type: integer
      label: 'Default quantity'
```

### Methods

The `ConfigurableQuickFormTrait` class add all the necessary methods required
by `ConfigurableQuickFormInterface` (which is used to designate a quick form as
"configurable"). Child classes can override these methods to customize their
behavior. At a minimum, most configurable quick form classes should override
`defaultConfiguration()`, `buildConfigurationForm()`, and
`submitConfigurationForm()`.

Available methods include:

- `defaultConfiguration()` - Provide an array of default configuration values.
- `buildConfigurationForm()` - Build the configuration form as an array using
  [Drupal Form API](https://www.drupal.org/docs/drupal-apis/form-api/introduction-to-form-api).
- `validateConfigurationForm()` - Perform validation on the user input.
- `submitConfigurationForm()` - Perform logic when the form is submitted to
  prepare the quick form configuration entity. This will not run if validation
  fails.

## Quick form configuration entities

Each quick form that is displayed to the user in farmOS is represented as a
[configuration entity](https://www.drupal.org/docs/drupal-apis/entity-api/configuration-entity).
Each configuration entity specifies which quick form plugin it uses (aka which
PHP class that extends from `QuickFormBase`), along with other information like
label, description, help text, and configuration settings (used by configurable
quick forms).

However, if a configuration entity is not saved, farmOS will try to provide a
"default instance" of the quick form plugin. From a module developer's
perspective, this means that the module does not need to provide any config
entity YML files in `config/install`. It can rely on farmOS's default quick
form instance logic to show the quick form.

In the case of configurable quick forms, a config entity will be automatically
created when the user modifies the quick form's configuration and submits the
configuration form.

Quick form configuration entities can also be used to override defaults,
including the label, description, and help text. They can also be used to
disable a quick form entirely by setting the config entity's `status` to
false.

If multiple configuration entities are provided for the same plugin, multiple
quick forms will be displayed in the UI. This is useful if you want to create
a set of similar quick forms with pre-set configuration options.

### Disable default instance

In some cases, a plugin may not want a "default instance" to be created.
Instead, they may want to require that a quick form configuration entity be
explicitly created. For example, if a plugin requires configuration settings,
but there isn't a sensible default for that configuration and user input is
required, a "default instance" may not be possible.

In that case, the plugin can add `requiresEntity = True` to its annotation,
which will tell farmOS not to create a default instance of the quick form.
The quick form will only be made available if a configuration entity is saved.

## Quick form actions

farmOS provides lists of logs and assets throughout its interface. Many of
these lists allow the user to select one or more entities and perform a
"bulk action" (eg: "Archive asset", "Assign owners", etc).

Quick form actions provide a shortcut to completing a quick form that performs
actions on or references existing entities.

This allows a user to select one or more entities from a list in farmOS, and be
redirected to the quick form with the selected entities passed in. These
selected entities can then be used in the quick form code in various ways.

### Providing a quick form action

To add a quick form action, three additional files are added to the module:

1. a PHP class in `src/Plugin/Action` that extends from `QuickFormActionBase`
2. an action config entity in `config/install/system.action.*.yml`
3. a `config/schema/[mymodule].schema.yml` file that describes action schema
   (see example below).

For example, an action that redirects to the "Harvest" quick form defined above
for prepopulating the "Asset" field would be provided as follows:

`/src/Plugin/Action/Harvest.php`:

```php
<?php

namespace Drupal\farm_quick_harvest\Plugin\Action;

use Drupal\farm_quick\Plugin\Action\QuickFormActionBase;

/**
 * Action for recording harvests.
 *
 * @Action(
 *   id = "harvest",
 *   label = @Translation("Record harvest"),
 *   type = "asset",
 *   confirm_form_route_name = "farm.quick.harvest"
 * )
 */
class Harvest extends QuickFormActionBase {

  /**
   * {@inheritdoc}
   */
  public function getQuickFormId(): string {
    return 'harvest';
  }

}
```

`/config/install/system.action.harvest.yml`:

```yml
langcode: en
status: true
dependencies:
  module:
    - asset
    - farm_quick_harvest
id: harvest
label: 'Record harvest'
type: asset
plugin: harvest
configuration: {  }
```

`/config/schema/farm_quick_harvest.schema.yml`:

```yml
# Schema for actions.
action.configuration.harvest:
  type: action_configuration_default
  label: 'Configuration for the harvest action'
```

Note that config entities are only created when the module is installed. In
order to add a config entity to a module that is already installed, an update
hook must be used to manually create the config entity.

### Using the selected entities

To get a list of the selected entities within the quick form class, add the
`QuickPrepopulateTrait` trait and use the `getPrepopulatedEntities()` helper
method that it provides. Specify the entity type and pass in the `$form_state`
object, as follows:

`$entities = $this->getPrepopulatedEntities('asset', $form_state);`

This will return a list of fully-loaded entity objects that can be used in the
quick form code.

The following is the same "Harvest" example as above, with two additions:

1. The `use QuickPrepopulateTrait;` line is added at the top of the class (as
   well as a corresponding `use` statement at the top of the file defining
   the full trait namespace).
2. The `getPrepopulatedEntities()` method is used to populate the `asset`
   field's default value.

```php
<?php

namespace Drupal\farm_quick_harvest\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickPrepopulateTrait;

/**
 * Harvest quick form.
 *
 * @QuickForm(
 *   id = "harvest",
 *   label = @Translation("Harvest"),
 *   description = @Translation("Record when a harvest takes place."),
 *   helpText = @Translation("Use this form to record a harvest."),
 *   permissions = {
 *     "create harvest log",
 *   }
 * )
 */
class Harvest extends QuickFormBase {

  use QuickLogTrait;
  use QuickPrepopulateTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Date+time selection field (defaults to now).
    $form['timestamp'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('now', \Drupal::currentUser()->getTimeZone()),
      '#required' => TRUE,
    ];

    // Asset reference field (allow multiple).
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assets'),
      '#target_type' => 'asset',
      '#tags' => TRUE,
      '#default_value' => $this->getPrepopulatedEntities('asset', $form_state),
    ];

    // Harvest quantity field.
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Draft a harvest log from the user-submitted data.
    $timestamp = $form_state->getValue('timestamp')->getTimestamp();
    $asset = $form_state->getValue('asset');
    $quantity = $form_state->getValue('quantity');
    $log = [
      'type' => 'harvest',
      'timestamp' => $timestamp,
      'asset' => $asset,
      'quantity' => [
        [
          'type' => 'standard',
          'value' => $quantity,
        ],
      ],
      'status' => 'done',
    ];

    // Create the log.
    $this->createLog($log);
  }

}
```
