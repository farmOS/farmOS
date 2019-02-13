#!/bin/bash
set -e

# Function for downloading and unpacking a farmOS release.
build_farmos_release () {

  # Download and unpack farmOS release.
  echo >&2 "Downloading farmOS $FARMOS_VERSION..."
  curl -SL "http://ftp.drupal.org/files/projects/farm-${FARMOS_VERSION}-core.tar.gz" -o /usr/src/farm-${FARMOS_VERSION}-core.tar.gz
  echo >&2 "Unpacking farmOS $FARMOS_VERSION..."
  tar -xvzf /usr/src/farm-${FARMOS_VERSION}-core.tar.gz -C /var/www/html/ --strip-components=1
}

# Function for building a dev branch of farmOS.
build_farmos_dev () {

  # Clone the farmOS installation profile, if it doesn't already exist.
  if ! [ -e /var/farmOS/build-farm.make ]; then
    git clone --branch $FARMOS_DEV_BRANCH https://git.drupal.org/project/farm.git /var/farmOS

  # Update it if it does exist.
  else
    git -C /var/farmOS pull origin $FARMOS_DEV_BRANCH
  fi

  # Build farmOS with Drush. Use the --working-copy flag to keep .git folders.
  drush make --working-copy --no-gitinfofile /var/farmOS/build-farm.make /tmp/farmOS \
  && cp -r /tmp/farmOS/. /var/www/html \
  && rm -r /tmp/farmOS
}

# Function for building farmOS.
build_farmos () {

  # If a development environment is desired, build from dev branch. Otherwise,
  # build from official packaged release.
  if $FARMOS_DEV; then
    build_farmos_dev
  else
    build_farmos_release
  fi
}

build_farmos
