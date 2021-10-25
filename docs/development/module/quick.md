# Quick forms

Quick forms provide a simplified user interface for common data entry tasks.

## Building quick forms

To add a quick form, a module can provide a PHP class in `src/Plugin/QuickForm`
that extends the `QuickFormBase` class.

For example, a simplified "Egg harvest" quick form would be provided as
follows:

`src/Plugin/QuickForm/Egg.php`:

```php
<?php

namespace Drupal\farm_egg\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;

/**
 * Egg harvest quick form.
 *
 * @QuickForm(
 *   id = "egg",
 *   label = @Translation("Egg harvest"),
 *   description = @Translation("Record when eggs are harvested."),
 *   helpText = @Translation("Use this form to record when eggs are havested."),
 *   permissions = {
 *     "create harvest log",
 *   }
 * )
 */
class Egg extends QuickFormBase {

  use QuickLogTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Egg quantity.
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#min' => 0,
      '#step' => 1,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Draft an egg harvest log from the user-submitted data.
    $quantity = $form_state->getValue('quantity');
    $log = [
      'name' => $this->t('Collected @count egg(s)', ['@count' => $quantity]),
      'type' => 'harvest',
      'quantity' => [
        [
          'measure' => 'count',
          'value' => $quantity,
          'unit' => $this->t('egg(s)'),
        ],
      ],
    ];

    // Create the log.
    $this->createLog($log);
  }

}
```

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

### Traits and helper methods

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
- `QuickQuantityTrait`
  - `createQuantity($values)` - Creates and returns a new quantity entity from
    an array of values.
- `QuickTermTrait`
  - `createTerm($values)` - Creates and returns a new term entity from an array
    of values.
  - `createOrLoadTerm($name, $vocabulary)` - Attempts to load an existing term,
    given a name and vocabulary. If the term does not exist, a new term will be
    created.

### Dependencies

All dependencies for a quick form should be declared in the module's
`*.info.yml` file.

## Quick form actions

Quick form actions provide a shortcut to completing a quick form that performs
actions on or references existing entities.

### Providing a quick form action

To add a quick form action, a module can provide a PHP class in
`src/Plugin/Action` that extends the `QuickFormActionBase` class.

For example, an action to complete the "Egg harvest" quick for select
assets would be provided as follows:

`src/Plugin/Action/EggHarvest.php`:

```php
<?php

namespace Drupal\farm_egg\Plugin\Action;

use Drupal\farm_quick\Plugin\Action\QuickFormActionBase;

/**
 * Action for recording egg harvests.
 *
 * @Action(
 *   id = "egg_harvest",
 *   label = @Translation("Record egg harvest"),
 *   type = "asset",
 *   confirm_form_route_name = "farm.quick.egg"
 * )
 */
class EggHarvest extends QuickFormActionBase {

  /**
   * {@inheritdoc}
   */
  public function getQuckFormId(): string {
    return 'egg';
  }

}
```

Once the plugin is created, an action config entity needs to be created:

`config/install/system.action.egg_harvest.yml`:

```yml
langcode: en
status: true
dependencies:
  module:
    - asset
    - farm_egg
id: egg_harvest
label: 'Record egg harvest'
type: asset
plugin: egg_harvest
configuration: {  }
```
