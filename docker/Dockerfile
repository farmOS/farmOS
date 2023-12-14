# Use the official Drupal 10 as base image.
FROM drupal:10.1 as baseimage

# Define common paths.
ENV FARMOS_PATH=/var/farmOS
ENV DRUPAL_PATH=/opt/drupal
ENV COMPOSER_HOME=${FARMOS_PATH}

##
# Build PHP extensions, GEOS and bcmath.
FROM baseimage as php-dependencies

# See https://git.osgeo.org/gitea/geos/php-geos
ARG PHP_GEOS_VERSION=e77d5a16abbf89a59d947d1fe49381a944762c9d
ADD https://github.com/libgeos/php-geos/archive/${PHP_GEOS_VERSION}.tar.gz /opt/php-geos.tar.gz
RUN apt-get update && apt-get install -y libgeos-dev \
  && ( tar xzf /opt/php-geos.tar.gz -C /opt/ \
    && cd /opt/php-geos-${PHP_GEOS_VERSION} \
    && ./autogen.sh \
    && ./configure \
    && make \
    && make install \
    )

# Install the BCMath PHP extension.
RUN docker-php-ext-install bcmath

# Setup dependencies and sources for composer installations.
FROM baseimage as composer-file

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=3.x
ARG PROJECT_VERSION=3.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project/raw/${PROJECT_VERSION}/composer.json

# Set the COMPOSER_MEMORY_LIMIT environment variable to unlimited.
ENV COMPOSER_MEMORY_LIMIT=-1
# Allow root to install plugins.
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install apt dependencies.
RUN apt-get update && apt-get install -y --no-install-recommends\
    # Install git and unzip (needed by Composer).
    git unzip

# Add the build-farmOS.sh script.
COPY build-farmOS.sh /usr/local/bin/

# Setup composer file for non interactive installation.
WORKDIR ${FARMOS_PATH}
RUN build-farmOS.sh

##
# Create layer with farmOS sources.
FROM composer-file as farmos-sources

# Install sources.
RUN composer install --no-dev

# Set the version in farm.info.yml.
RUN sed -i "s|version: 3.x|version: ${FARMOS_VERSION}|g" ${FARMOS_PATH}/web/profiles/farm/farm.info.yml

##
# Build final image.
FROM baseimage as farmos-baseimage

# Set Apache ServerName directive globally to suppress AH00558 message.
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable PHP dependencies.
COPY --from=php-dependencies /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
RUN docker-php-ext-enable geos bcmath

# Add custom PHP configurations.
COPY conf.d/ /usr/local/etc/php/conf.d

# Install apt dependencies and clean up.
RUN apt-get update && apt-get install -y \
    # Install postgresql-client so Drush can connect to the database.
    postgresql-client \
    # Install libgeos-c1v5 so geos php extension can use libgeos_c.so.1.
    libgeos-c1v5 \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean \
    # Clean up ${DRUPAL_PATH}.
    && rm -r ${DRUPAL_PATH} \
    && mkdir ${DRUPAL_PATH}

# Set the entrypoint.
COPY --chown=www-data docker-entrypoint.sh /usr/local/bin/

# Set the working directory, entrypoint and command.
WORKDIR ${DRUPAL_PATH}
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]


##
# Final image.
FROM farmos-baseimage

# Add farmOS sources.
COPY --from=farmos-sources --chown=www-data:www-data ${FARMOS_PATH} ${DRUPAL_PATH}

# Add the build-farmOS.sh script to keep compatibility with dev image.
COPY build-farmOS.sh /usr/local/bin/
