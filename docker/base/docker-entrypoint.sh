#!/bin/bash
set -e

###
# This entrypoint script will check to see if certain directories are empty
# (as is the case when a directory is bind-mounted from the host), and will
# populate them from the pre-built farmOS codebase in the image.
###

# If the /var/www/html directory is empty, populate it from pre-built files.
if [ -d /var/www/html ] && ! [ "$(ls -A /var/www/html/)" ]; then
  echo "farmOS codebase not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/. /var/www/html
fi

# If the sites directory is empty, populate it from pre-built files.
if [ -d /var/www/html/web/sites ] && ! [ "$(ls -A /var/www/html/web/sites/)" ]; then
  echo "farmOS sites directory not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/web/sites/. /var/www/html/web/sites
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
