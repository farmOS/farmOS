# Use the official Drupal 10 image to build GEOS PHP extension.
FROM drupal:10.1 as baseimage

# Define common paths.
ENV FARMOS_PATH=/var/farmOS
ENV DRUPAL_PATH=/opt/drupal
ENV COMPOSER_HOME=${FARMOS_PATH}

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=3.x
ARG PROJECT_VERSION=3.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project/raw/${PROJECT_VERSION}/composer.json

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

##
# Setup dependencies and sources for composer installations.
FROM baseimage as composer-file

# Set the COMPOSER_MEMORY_LIMIT environment variable to unlimited.
ENV COMPOSER_MEMORY_LIMIT=-1

# Install apt dependencies.
RUN apt-get update && apt-get install -y \
    # Install git and unzip (needed by Composer).
    git unzip

# Add the build-farmOS.sh script.
COPY build-farmOS.sh /usr/local/bin/

WORKDIR ${FARMOS_PATH}
# Setup composer file for non interactive installation.
RUN build-farmOS.sh

##
# Create layer with farmOS sources.
FROM composer-file as farmos-sources

# Install sources.
RUN composer install --no-dev

# Set the version in farm.info.yml.
RUN sed -i "s|version: 3.x|version: ${FARMOS_VERSION}|g" ${FARMOS_PATH}/web/profiles/farm/farm.info.yml

##
# Create layer with farmOS dev sources.
FROM composer-file as farmos-dev-sources

# Install sources.
RUN composer install

# Set the version in farm.info.yml.
RUN sed -i "s|version: 3.x|version: ${FARMOS_VERSION}|g" ${FARMOS_PATH}/web/profiles/farm/farm.info.yml

##
# Add final image dependencies and configurations.
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
# Development image.
FROM farmos-baseimage as farmos-dev

# Change the user/group IDs of www-data inside the image to match the ID of the
# developer's user on the host machine. This allows Composer to create files
# owned by www-data inside the container, while keeping those files editable by
# the developer outside of the container.
# This defaults to 1000, based on the assumption that the developer is running
# as UID 1000 on the host machine. It can be overridden at image build time with:
# --build-arg WWW_DATA_ID=$(id -u)
ARG WWW_DATA_ID=1000
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data

# Add farmOS sources
COPY --from=composer-file --chown=www-data:www-data ${FARMOS_PATH} ${FARMOS_PATH}

# Install and configure XDebug.
RUN yes | pecl install xdebug \
    && docker-php-ext-enable xdebug

# Add opcache revalidation frequency configuration.
COPY dev/conf.d/ /usr/local/etc/php/conf.d

# Add Configurations for PHP CodeSniffer, PHPStan
COPY --chown=www-data:www-data ./dev/files/ ${FARMOS_PATH}

# Add farmOS dev sources.
COPY --from=farmos-dev-sources --chown=www-data:www-data ${FARMOS_PATH} ${FARMOS_PATH}

# Configure PHPunit.
RUN ${FARMOS_PATH}/phpunit.sh


##
# Final image.
FROM farmos-baseimage

# Add farmOS sources.
COPY --from=farmos-sources --chown=www-data:www-data ${FARMOS_PATH} ${DRUPAL_PATH}
