#!/bin/bash
set -e

# If the sites folder exists, preserve it by temporarily moving it up one dir.
if [ -e /var/www/html/sites ]; then
  echo >&2 "Existing sites folder detected. Moving temporarily..."
  mv /var/www/html/sites /var/www/sites
fi

# Remove the existing farmOS codebase, if it exists.
if [ -e /var/www/html/index.php ]; then
  echo >&2 "Removing existing farmOS codebase..."
  find /var/www/html -mindepth 1 -delete
fi

# Download the packaged release of farmOS, if a dev environment is not desired.
if ! $FARMOS_DEV; then

  # Download and unpack farmOS release.
  echo >&2 "Downloading farmOS $FARMOS_VERSION..."
  curl -SL "http://ftp.drupal.org/files/projects/farm-${FARMOS_VERSION}-core.tar.gz" -o /usr/src/farm-${FARMOS_VERSION}-core.tar.gz
  echo >&2 "Unpacking farmOS $FARMOS_VERSION..."
  tar -xvzf /usr/src/farm-${FARMOS_VERSION}-core.tar.gz -C /var/www/html/ --strip-components=1

# Or, build a development environment.
else

  # Clone the farmOS installation profile, if it doesn't already exist.
  if ! [ -e /var/farmOS/build-farm.make ]; then
    git clone --branch $FARMOS_DEV_BRANCH https://git.drupal.org/project/farm.git /var/farmOS

  # Update it if it does exist.
  else
    git -C /var/farmOS pull origin $FARMOS_DEV_BRANCH
  fi

  # Build farmOS with Drush. Use the --working-copy flag to keep .git folders.
  drush make --working-copy /var/farmOS/build-farm.make /tmp/farmOS \
  && cp -r /tmp/farmOS/. /var/www/html \
  && rm -r /tmp/farmOS

fi

# Restore the sites folder.
if [ -e /var/www/sites ]; then
  echo >&2 "Restoring sites directory..."
  rm -r /var/www/html/sites \
  && mv /var/www/sites /var/www/html/sites
fi

# Change ownership of the sites folder.
chown -R www-data:www-data /var/www/html/sites

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"

