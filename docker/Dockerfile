##
# Alias the Drupal base image for use in other build stages.
# We do this so that the Drupal image version only needs to be updated here.
FROM drupal:10.4-php8.3 AS baseimage

# Define common paths.
# BUILD_PATH is where the farmOS codebase will be built.
# DRUPAL_PATH is where the farmOS codebase will be copied to after build.
# We keep these separate to faciliate the docker-entrypoint.sh script's logic
# for populating empty bind-mounted host directories.
# The Drupal base image symlinks /var/www/html to /opt/drupal and configures
# /opt/drupal/web as the DocumentRoot.
ENV BUILD_PATH=/var/farmOS
ENV DRUPAL_PATH=/opt/drupal

##
# Build/install PHP extensions.
FROM baseimage AS php-dependencies

# Build and install the GEOS PHP extension.
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

# Install the BCMath and Exif PHP extensions.
RUN docker-php-ext-install bcmath exif

##
# Build common requirements for production and development images.
FROM baseimage AS common

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=4.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=4.x

# Set Apache ServerName directive globally to suppress AH00558 message.
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable PHP dependencies.
COPY --from=php-dependencies /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
RUN docker-php-ext-enable bcmath exif geos

# Add custom PHP configurations.
COPY conf.d/ /usr/local/etc/php/conf.d

# Install apt dependencies.
RUN apt-get update && apt-get install -y \
    # Install git and unzip (needed by Composer).
    git unzip \
    # Install postgresql-client so Drush can connect to the database.
    postgresql-client \
    # Install libgeos-c1v5 so geos php extension can use libgeos_c.so.1.
    libgeos-c1v5 \
  && rm -rf /var/lib/apt/lists/* \
  && apt-get clean

# Set the COMPOSER_MEMORY_LIMIT environment variable to unlimited.
ENV COMPOSER_MEMORY_LIMIT=-1

# Set COMPOSER_ALLOW_SUPERUSER=1 to allow plugins to run as root/super user.
ENV COMPOSER_ALLOW_SUPERUSER=1

# Add the build-farmOS.sh script.
COPY build-farmOS.sh /usr/local/bin/
RUN chmod a+x /usr/local/bin/build-farmOS.sh

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

##
# Build the production image.
FROM common AS production

# Build the farmOS codebase in ${BUILD_PATH} with the --no-dev flag.
# Change the ownership of the sites directory and copy the farmOS codebase into ${DRUPAL_PATH}.
RUN mkdir ${BUILD_PATH} \
  && /usr/local/bin/build-farmOS.sh --no-dev \
  && chown -R www-data:www-data ${BUILD_PATH}/web/sites \
  && rm -r ${DRUPAL_PATH} && cp -rp ${BUILD_PATH} ${DRUPAL_PATH}

## Build the development image.
FROM common AS dev

# Install and enable XDebug extension.
RUN yes | pecl install xdebug \
  && docker-php-ext-enable xdebug

# Add opcache revalidation frequency configuration.
COPY dev/conf.d/ /usr/local/etc/php/conf.d

# Change the user/group IDs of www-data inside the image to match the ID of the
# developer's user on the host machine. This allows Composer to create files
# owned by www-data inside the container, while keeping those files editable by
# the developer outside of the container.
# This defaults to 1000, based on the assumption that the developer is running
# as UID 1000 on the host machine. It can be overridden at image build time with:
# --build-arg WWW_DATA_ID=$(id -u)
ARG WWW_DATA_ID=1000
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data

# Create a ${BUILD_PATH} directory owned by www-data.
RUN mkdir ${BUILD_PATH} && chown www-data:www-data ${BUILD_PATH}

# Change to the www-data user.
USER www-data

# Build the farmOS codebase in ${BUILD_PATH}.
RUN /usr/local/bin/build-farmOS.sh

# Add dev configurations for PHP CodeSniffer, PHPStan, and Rector.
COPY --chown=www-data ./dev/files/ ${BUILD_PATH}/

# Configure PHPUnit.
RUN cp -p ${BUILD_PATH}/web/core/phpunit.xml.dist ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnIncomplete="true"' ${BUILD_PATH}/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnSkipped="true"' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://www"|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\'''\''|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\''["chrome", { "chromeOptions": { "w3c": false, "args": ["--disable-gpu","--headless", "--no-sandbox"] } }, "http://chrome:4444/wd/hub"]'\''|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|\./|\./web/core/|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|\.\./web/core/|\./web/|g' ${BUILD_PATH}/phpunit.xml \
  && sed -i 's|  </php>|    <env name="SYMFONY_DEPRECATIONS_HELPER" value="disabled"/>'"\n"'  </php>|g' ${BUILD_PATH}/phpunit.xml \
  && mkdir -p ${BUILD_PATH}/web/sites/simpletest/browser_output

# Change back to the root user.
USER root

# Copy the farmOS codebase into ${DRUPAL_PATH}.
RUN rm -r ${DRUPAL_PATH} && cp -rp ${BUILD_PATH} ${DRUPAL_PATH}

# Create a Composer config directory for the www-data user.
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer

##
# Build the final production image.
FROM production
