#!/bin/bash
set -e

# Function for archiving the sites folder.
archive_sites () {

	# If the sites folder exists, preserve it by temporarily archiving it.
	if [ -e /var/www/html/sites ]; then
		echo >&2 "Existing sites folder detected. Archiving temporarily..."
		tar -czf /var/www/html/sites.tar.gz /var/www/html/sites
	fi
}

# Function for restoring the sites folder.
restore_sites () {

	# Restore the sites folder.
	if [ -e /var/www/html/sites.tar.gz ]; then
		echo >&2 "Restoring sites directory..."
		rm -r /var/www/html/sites \
		&& tar -xzf /var/www/html/sites.tar.gz -C /var/www/html/ --strip-components=3 \
		&& rm /var/www/html/sites.tar.gz
	fi

	# Change ownership of the sites folder.
  chown -R www-data:www-data /var/www/html/sites
}

# Function for deleting the farmOS codebase.
delete_farmos () {

	# Remove the existing farmOS codebase, if it exists.
	# Exclude sites.tar.gz.
	if [ -e /var/www/html/index.php ]; then
		echo >&2 "Removing existing farmOS codebase..."
		find /var/www/html ! -name 'sites.tar.gz' -mindepth 1 -delete
	fi
}

# Function for downloading and unpacking a farmOS release.
build_farmos_release () {

  # Download and unpack farmOS release.
  echo >&2 "Downloading farmOS $FARMOS_VERSION..."
  curl -SL "http://ftp.drupal.org/files/projects/farm-${FARMOS_VERSION}-core.tar.gz" -o /usr/src/farm-${FARMOS_VERSION}-core.tar.gz
  echo >&2 "Unpacking farmOS $FARMOS_VERSION..."
  tar -xvzf /usr/src/farm-${FARMOS_VERSION}-core.tar.gz -C /var/www/html/ --strip-components=1
}

# Function for building a dev branch of farmOS.
build_farmos_dev () {

  # Clone the farmOS installation profile, if it doesn't already exist.
  if ! [ -e /var/farmOS/build-farm.make ]; then
    git clone --branch $FARMOS_DEV_BRANCH https://git.drupal.org/project/farm.git /var/farmOS

  # Update it if it does exist.
  else
    git -C /var/farmOS pull origin $FARMOS_DEV_BRANCH
  fi

  # Build farmOS with Drush. Use the --working-copy flag to keep .git folders.
  drush make --working-copy --no-gitinfofile /var/farmOS/build-farm.make /tmp/farmOS \
  && cp -r /tmp/farmOS/. /var/www/html \
  && rm -r /tmp/farmOS
}

# Function for building farmOS.
build_farmos () {

  # If a development environment is desired, build from dev branch. Otherwise,
  # build from official packaged release.
  if $FARMOS_DEV; then
    build_farmos_dev
  else
    build_farmos_release
  fi
}

# Function for determining whether a rebuild is required.
rebuild_required () {

	# If farm.info doesn't exist, a rebuild is required.
	if ! [ -e /var/www/html/profiles/farm/farm.info ]; then
	  echo >&2 "farmOS not detected. Building..."
    return 0
	else
	  echo >&2 "An existing farmOS codebase was detected."
	  echo >&2 "To force a rebuild, delete profiles/farm/farm.info and restart the container."
	  return 1
	fi
}

# Rebuild farmOS, if necessary.
if rebuild_required; then

	# Archive the sites folder and delete the farmOS codebase.
	archive_sites
	delete_farmos

	# Build farmOS.
	build_farmos

	# Restore the sites folder.
	restore_sites
fi

# Execute the arguments passed into this script.
echo "Attempting: $@"
exec "$@"

