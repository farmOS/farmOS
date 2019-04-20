#!/bin/bash
set -e

# If the webroot directory is empty, copy from /tmp/www.
if ! [ "$(ls -A /var/www/html/)" ]; then
  cp -rp /tmp/www/. /var/www/html
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
