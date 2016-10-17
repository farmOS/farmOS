#!/bin/bash
set -e

# If we are building a development environment, and the farmOS codebase does
# not exist, build it.
if $FARMOS_DEV && [ ! -e /var/farmOS/build-farm.make ]; then

  # Clone the farmOS installation profile.
  git clone --branch $FARMOS_DEV_BRANCH https://git.drupal.org/project/farm.git /var/farmOS

  # Build farmOS with Drush.
  rm -rf /var/www/html/* \
  && drush make /var/farmOS/build-farm.make /tmp/farmOS \
  && cp -r /tmp/farmOS/. /var/www/html \
  && rm -r /tmp/farmOS

  # Change ownership of the sites folder.
  chown -R www-data:www-data /var/www/html/sites

else

  # Download and unpack farmOS release.
  echo >&2 "Downloading farmOS $FARMOS_VERSION..."
  curl -SL "http://ftp.drupal.org/files/projects/farm-${FARMOS_VERSION}-core.tar.gz" -o /usr/src/farm-${FARMOS_VERSION}-core.tar.gz
  echo >&2 "Unpacking farmOS $FARMOS_VERSION..."
  rm -rf /var/www/html/*
  tar -xvzf /usr/src/farm-${FARMOS_VERSION}-core.tar.gz -C /var/www/html/ --strip-components=1

  # Change ownership of the sites folder.
  chown -R www-data:www-data /var/www/html/sites

fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"

