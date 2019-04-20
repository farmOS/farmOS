#!/bin/bash
set -e

# If the sites directory is empty, unpack /tmp/sites.tar.gz.
if ! [ "$(ls -A /var/www/html/sites/)" ]; then
  tar -xvzf /tmp/sites.tar.gz -C /var/www/html/sites/ --strip-components=4
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"
