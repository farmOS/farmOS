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

# Replace the farmOS repository and version in composer.json.
# If FARMOS_VERSION is a valid semantic versioning string, we assume that it is
# a tagged version, and replace the entire version string in composer.json.
# Or, if FARMOS_VERSION is not "2.x", we assume that it is a branch, and
# prepend it with "dev-". Otherwise (FARMOS_VERSION is "2.x"), do nothing.
sed -i 's|"repositories": \[|"repositories": \[ {"type": "git", "url": "'"${FARMOS_REPO}"'"},|g' composer.json
if [[ "${FARMOS_VERSION}" =~ ^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)(-((0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9][0-9]*|[0-9]*[a-zA-Z-][0-9a-zA-Z-]*))*))?(\+([0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*))?$ ]]; then
  sed -i 's|"farmos/farmos": "2.x-dev"|"farmos/farmos": "'"${FARMOS_VERSION}"'"|g' composer.json
elif ! [ "${FARMOS_VERSION}" = "2.x" ]; then
  sed -i 's|"farmos/farmos": "2.x-dev"|"farmos/farmos": "dev-'"${FARMOS_VERSION}"'"|g' composer.json
fi

# Create a temporary Composer cache directory.
export COMPOSER_HOME="$(mktemp -d)"

# Run composer install with optional arguments passed into this script.
if [ $# -eq 0 ]; then
  composer install
else
  composer install "$*"
fi

# Remove the Composer cache directory.
rm -rf "$COMPOSER_HOME"
