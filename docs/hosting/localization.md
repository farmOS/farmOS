# Localization to another language

* Enable the **Farm Localization** module (`farm_l10n`) that comes with farmOS.

* Add your language at `/admin/config/regional/language`
    * Set this new language as default language, if desired.

* Go to `/admin/config/regional/translate/update` and check manually for
  tranlations and update them.

* If there are some strings not translated, you can do it at
  `/admin/config/regional/translate/translate` and, if possible, add them to
  https://localize.drupal.org.
    * You could download and install the **Localization Client** module to do
      both tasks together: [https://www.drupal.org/project/l10n_client](https://www.drupal.org/project/l10n_client)

