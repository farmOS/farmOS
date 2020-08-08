#!/bin/bash
set -e

###
# This entrypoint script will check to see if certain directories were mounted
# as volumes (and are therefore empty), and will copy files from the pre-built
# farmOS codebase in /var/farmOS to populate them.
###

# If the /var/www/html directory is empty, copy from /var/farmOS.
if ! [ "$(ls -A /var/www/html/)" ]; then
  echo "farmOS codebase not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/. /var/www/html
fi

# If the sites directory is empty, copy from /var/farmOS/web/sites.
if ! [ "$(ls -A /var/www/html/web/sites/)" ]; then
  echo "farmOS sites directory not detected. Copying from pre-built files in the Docker image."
  cp -rp /var/farmOS/web/sites/. /var/www/html/web/sites
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
