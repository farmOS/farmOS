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
core_version_requirement: ^9
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
      '#default_value' => new DrupalDateTime(),
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

To add a quick form action, two additional files are added to the module:

1. a PHP class in `src/Plugin/Action` that extends from `QuickFormActionBase`
2. an action config entity in `config/install/system.action.*.yml`

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
      '#default_value' => new DrupalDateTime(),
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
