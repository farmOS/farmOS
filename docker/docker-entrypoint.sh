#!/bin/bash
set -e

###
# This entrypoint script will check to see if certain directories are empty
# (as is the case when a directory is bind-mounted from the host), and will
# populate them from the pre-built farmOS codebase in the image.
###

# If the Drupal directory is empty, populate it from pre-built files.
if [ -d /opt/drupal ] && ! [ "$(ls -A /opt/drupal/)" ]; then
  echo "farmOS codebase not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/. /opt/drupal
fi

# If the sites directory is empty, populate it from pre-built files.
if [ -d /opt/drupal/web/sites ] && ! [ "$(ls -A /opt/drupal/web/sites/)" ]; then
  echo "farmOS sites directory not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/web/sites/. /opt/drupal/web/sites
fi

if [ -n "$FARMOS_FS_READY_SENTINEL_FILENAME" ]; then
  echo "ready" > "$FARMOS_FS_READY_SENTINEL_FILENAME"
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
