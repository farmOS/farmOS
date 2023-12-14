# Inherit from the farmOS 3.x image.
FROM farmos/farmos:3.x

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=3.x
ARG PROJECT_VERSION=3.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project/raw/${PROJECT_VERSION}/composer.json

# Install and enable XDebug extension.
RUN yes | pecl install xdebug \
  && docker-php-ext-enable xdebug

# Add opcache revalidation frequency configuration.
COPY conf.d/ /usr/local/etc/php/conf.d

# Change the user/group IDs of www-data inside the image to match the ID of the
# developer's user on the host machine. This allows Composer to create files
# owned by www-data inside the container, while keeping those files editable by
# the developer outside of the container.
# This defaults to 1000, based on the assumption that the developer is running
# as UID 1000 on the host machine. It can be overridden at image build time with:
# --build-arg WWW_DATA_ID=$(id -u)
ARG WWW_DATA_ID=1000
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data

# Create a fresh ${FARMOS_PATH} directory owned by www-data.
# We do this in two steps because of a known issue with Moby.
# @see https://github.com/farmOS/farmOS/pull/440
RUN rm -rf ${FARMOS_PATH}
RUN mkdir ${FARMOS_PATH} && chown www-data:www-data ${FARMOS_PATH}

# Install apt dependencies, they are not part of baseimage after decouple composer.
RUN apt-get update && apt-get install -y \
    # Install git and unzip (needed by Composer).
    git unzip

# Change to the www-data user.
USER www-data

# Work on FarmOS folder.
WORKDIR ${FARMOS_PATH}

# Build the farmOS codebase in ${FARMOS_PATH}.
RUN /usr/local/bin/build-farmOS.sh && composer install
RUN sed -i "s|version: 3.x|version: ${FARMOS_VERSION}|g" ${FARMOS_PATH}/web/profiles/farm/farm.info.yml


# Add Configurartions for PHP CodeSniffer, PHPStan.
COPY --chown=www-data ./files/ ${FARMOS_PATH}/

# Configure PHPUnit.
RUN cp -p ${FARMOS_PATH}/web/core/phpunit.xml.dist ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnIncomplete="true"' ${FARMOS_PATH}/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnSkipped="true"' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://www"|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\'''\''|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\''["chrome", { "chromeOptions": { "w3c": false, "args": ["--disable-gpu","--headless", "--no-sandbox"] } }, "http://chrome:4444/wd/hub"]'\''|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|\./|\./web/core/|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|\.\./web/core/|\./web/|g' ${FARMOS_PATH}/phpunit.xml \
  && sed -i 's|  </php>|    <env name="SYMFONY_DEPRECATIONS_HELPER" value="disabled"/>'"\n"'  </php>|g' ${FARMOS_PATH}/phpunit.xml \
  && mkdir -p ${FARMOS_PATH}/web/sites/simpletest/browser_output

# Change back to the root user.
USER root

# Copy the farmOS codebase into ${DRUPAL_PATH}.
RUN rm -r ${DRUPAL_PATH} && cp -rp ${FARMOS_PATH} ${DRUPAL_PATH}

# Create a Composer config directory for the www-data user.
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer


# Setup DRUPAL working path.
WORKDIR ${DRUPAL_PATH}
