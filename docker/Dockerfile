# Inherit from the farmOS 2.x base image.
FROM farmos/farmos:2.x-base

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=2.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=2.x

# Switch to the /var/farmOS directory as the www-data user.
WORKDIR /var/farmOS
USER www-data

# Generate an empty project from farmos/project.
# Clone the composer-project from a specific repo+revision, replace the farmOS
# repo+revision within composer.json, and run composer install with --no-dev.
RUN git clone ${PROJECT_REPO} project && mv project/.git ./.git && rm -rf project && git checkout ${PROJECT_VERSION} && git reset --hard \
  && sed -i 's|"repositories": \[|"repositories": \[ {"type": "git", "url": "'"${FARMOS_REPO}"'"},|g' composer.json \
  && if ! [ "${FARMOS_VERSION}" = "2.x" ]; then \
    sed -i 's|"farmos/farmos": "2.x-dev"|"farmos/farmos": "dev-'"${FARMOS_VERSION}"'"|g' composer.json; \
  fi \
  && export COMPOSER_HOME="$(mktemp -d)" \
  && composer install --no-dev \
  && rm -rf "$COMPOSER_HOME"

# Switch back to the /opt/drupal directory as the root user.
WORKDIR /opt/drupal
USER root

# Copy /var/farmOS to /opt/drupal.
RUN rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal
