# Inherit from the farmOS 2.x-dev image.
# @todo
# In the future, once we have a tagged release, we can inherit directly from the
# farmos/farmos:2.x-base image, and run our own composer install with the
# --no-dev flag below this line.
#FROM farmos/farmos:2.x-base as build
FROM farmos/farmos:2.x-dev as build

# Use Docker's multi-stage build process to create a final image that includes
# the farmOS codebase without Composer or other build tools. Inherit from the
# farmOS 2.x-base image, which includes all the system dependencies needed by
# farmOS.
FROM farmos/farmos:2.x-base

# Copy prebuilt codebase into the container.
# We copy both /var/farmOS and /opt/drupal so that docker-entrypoint.sh
# can replace files in mounted volumes as necessary.
COPY --from=build /var/farmOS/ /var/farmOS/
COPY --from=build /opt/drupal/ /opt/drupal/
