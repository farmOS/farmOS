---
title: Updates
---

# Automated updates

farmOS modules may change and evolve over time. If these changes require
updates to a farmOS database or configuration, then update logic should be
provided so that users of the module can perform the necessary changes
automatically when they update to the new version.

This logic can be supplied via implementations of `hook_update_N()`.

For more information, see the documentation for Drupal's
[Update API](https://www.drupal.org/docs/drupal-apis/update-api/).
