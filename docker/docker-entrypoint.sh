#!/bin/bash
set -e

# If the sites directory is empty, copy from /tmp/sites.
if ! [ "$(ls -A /var/www/html/sites/)" ]; then
  cp -rp /tmp/sites/. /var/www/html/sites
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
