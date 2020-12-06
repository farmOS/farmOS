#!/bin/bash
set -e

# If the webroot directory is empty, copy from /tmp/www.
if ! [ "$(ls -A /var/www/html/)" ]; then
  echo "farmOS webroot not detected. Copying from pre-built codebase in the Docker image."
  cp -rp /tmp/www/. /var/www/html

  # If the FARMOS_INSTALL_CMD variable is defined run the expression it contains. This allows
  # for a drush site-install command to be specified as part of a docker-compose.yml file.
  if [ -n "$FARMOS_INSTALL_CMD" ]; then
    echo "Running '$FARMOS_INSTALL_CMD'"
    eval "$FARMOS_INSTALL_CMD"
  fi
fi

# If the FARMOS_DEV_MODULES_DIRECTORY variable is defined, symlink and use drush to enable
# each module contained therein. This allows modules to be bind-mounted for live development.
if [ -n "$FARMOS_DEV_MODULES_DIRECTORY" ]; then
  find "$FARMOS_DEV_MODULES_DIRECTORY" -maxdepth 1 -mindepth 1 -execdir sh -c '\
      echo Symlinking and drush enabling $(basename {}) && \
      ln -sTf "$FARMOS_DEV_MODULES_DIRECTORY"/$(basename {}) /var/www/html/sites/all/modules/$(basename {}) && \
      drush --root=/var/www/html pm-enable --yes $(basename {})' \;
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
