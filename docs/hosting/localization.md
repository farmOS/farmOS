# Localization to another language

* Enable **Locale** module

* * Download and enable **Localization_update** module (https://www.drupal.org/project/l10n_update), For example, using drush:

    ```drush en l10n_update```

* Add your language at */admin/config/regional/language*
    * Set this new language as default language, if desired.
    * Set the Detection and Selection options at */admin/config/regional/language/configure*


* Configure the Localization_update Module at */admin/config/regional/language/update*

   * Suggested:

>    Check for updates: weekly

>    Import behaviour: Only overwrite imported translations, customized translations are kept.

   * If you are in a hurry, first time, go to */admin/config/regional/translate/update* and check manually for tranlations and update them.

   * If there are some strings not translated, you can do it at */admin/config/regional/translate/translate* and, if possible, add them to https://localize.drupal.org. You could use this additional module to do both tasks together: https://www.drupal.org/project/l10n_client
