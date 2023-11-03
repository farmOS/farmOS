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

# Add the farmOS repository to composer.json.
composer config repositories.farmos git ${FARMOS_REPO}

# Require the correct farmOS version in composer.json. Defaults to 3.x.
# If FARMOS_VERSION is not a valid semantic versioning string, we assume that
# it is a branch, and prepend it with "dev-".
# Otherwise FARMOS_VERSION is a valid semantic versioning string. We assume
# that it is a tagged version and require that version.
if [ "${FARMOS_VERSION}" = "3.x" ]; then
  FARMOS_COMPOSER_VERSION="3.x-dev"
elif [[ ! "${FARMOS_VERSION}" =~ ^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)(-((0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*))*))?(\+([0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*))?$ ]]; then
  FARMOS_COMPOSER_VERSION="dev-${FARMOS_VERSION}"
else
  FARMOS_COMPOSER_VERSION="${FARMOS_VERSION}"
fi
composer require farmos/farmos ${FARMOS_COMPOSER_VERSION} --no-install

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
