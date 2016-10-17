#!/bin/bash
set -e

# If the farmOS codebase does not exist, build it.
if ! [ -e /var/farmOS/build-farm.make ]; then

  # Clone the farmOS installation profile.
  git clone --branch $FARMOS_DEV_BRANCH https://git.drupal.org/project/farm.git /var/farmOS

  # Build farmOS with Drush.
  drush make /var/farmOS/build-farm.make /tmp/farmOS \
  && cp -r /tmp/farmOS/. /var/www/html \
  && rm -r /tmp/farmOS

  # Change ownership of the sites folder.
  chown -R www-data:www-data /var/www/html/sites
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"

