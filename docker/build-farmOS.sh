#!/bin/bash
set -e

###
# This script will build the farmOS codebase in /var/farmOS.
###

# If /var/farmOS is not empty, bail.
if [ "$(ls -A /var/farmOS/)" ]; then
  exit 1
fi

# Make /var/farmOS the working directory.
cd /var/farmOS

# Generate an empty Composer project project and checkout a specific version.
git clone ${PROJECT_REPO} project
mv project/.git ./.git
rm -rf project
git checkout ${PROJECT_VERSION}
git reset --hard

# Create a temporary Composer cache directory.
export COMPOSER_HOME="$(mktemp -d)"

# If FARMOS_VERSION is a valid semantic versioning string, we assume that it is
# a tagged version.
IS_TAGGED_RELEASE=false
if [[ "${FARMOS_VERSION}" =~ ^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)(-((0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*))*))?(\+([0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*))?$ ]]; then
  IS_TAGGED_RELEASE=true
fi

# Add the farmOS repository to composer.json (if this is not a tagged release).
if [ "${IS_TAGGED_RELEASE}" = false ]; then
  composer config repositories.farmos git ${FARMOS_REPO}
fi

# Require the correct farmOS version in composer.json.
# If FARMOS_VERSION is 3.x, we will require 3.x-dev.
if [ "${FARMOS_VERSION}" = "3.x" ]; then
  FARMOS_COMPOSER_VERSION="3.x-dev"
# Or, if this is a tagged release, require the tag version.
elif [ "${IS_TAGGED_RELEASE}" = true ]; then
  FARMOS_COMPOSER_VERSION="${FARMOS_VERSION}"
# Otherwise, we assume that FARMOS_VERSION is a branch, and prepend "dev-".
else
  FARMOS_COMPOSER_VERSION="dev-${FARMOS_VERSION}"
fi
composer require farmos/farmos:${FARMOS_COMPOSER_VERSION} --no-install

# Add allow-plugins config.
allowedPlugins=(
  "composer/installers"
  "cweagans/composer-patches"
  "dealerdirect/phpcodesniffer-composer-installer"
  "drupal/core-composer-scaffold"
  "oomphinc/composer-installers-extender"
  "phpstan/extension-installer"
  "wikimedia/composer-merge-plugin"
)
for plugin in ${allowedPlugins[@]}; do
  composer config --no-plugins allow-plugins.$plugin true
done

# Run composer install with optional arguments passed into this script.
if [ $# -eq 0 ]; then
  composer install
else
  composer install "$*"
fi

# Set the version in farm.info.yml.
sed -i "s|version: 3.x|version: ${FARMOS_VERSION}|g" /var/farmOS/web/profiles/farm/farm.info.yml

# Remove the Composer cache directory.
rm -rf "$COMPOSER_HOME"
