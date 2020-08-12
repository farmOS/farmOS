# Inherit from the farmOS 2.x base image.
FROM farmos/farmos:2.x-base

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=2.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=2.x

# Change the user/group IDs of www-data inside the image to match the ID of the
# developer's user on the host machine. This allows Composer to create files
# owned by www-data inside the container, while keeping those files editable by
# the developer outside of the container.
# This defaults to 1000, based on the assumption that the developer is running
# as UID 1000 on the host machine. It can be overridden at image build time with:
# --build-arg WWW_DATA_ID=$(id -u)
ARG WWW_DATA_ID=1000
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data \
  && chown www-data:www-data /var/farmOS

# Install git and unzip (needed by Composer).
RUN apt-get update -y \
  && apt-get install -y git unzip

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Create a Composer config directory for the www-data user.
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer

# Switch to the /var/farmOS directory as the www-data user.
WORKDIR /var/farmOS
USER www-data

# Set the COMPOSER_MEMORY_LIMIT environment variable to unlimited.
ENV COMPOSER_MEMORY_LIMIT=-1

# Generate an empty project from farmos/project.
# Clone the composer-project from a specific repo+revision, replace the farmOS
# repo+revision within composer.json, and run composer install.
RUN git clone ${PROJECT_REPO} project && mv project/.git ./.git && rm -rf project && git checkout ${PROJECT_VERSION} && git reset --hard \
  && sed -i 's|"repositories": \[|"repositories": \[ {"type": "git", "url": "'"${FARMOS_REPO}"'"},|g' composer.json \
  && if ! [ "${FARMOS_VERSION}" = "2.x" ]; then \
    sed -i 's|"farmos/farmos": "2.x-dev"|"farmos/farmos": "dev-'"${FARMOS_VERSION}"'"|g' composer.json; \
  fi \
  && composer install

# Configure PHPUnit.
RUN cp web/core/phpunit.xml.dist phpunit.xml \
  && sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' phpunit.xml \
  && sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://localhost"|g' phpunit.xml \
  && sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' phpunit.xml \
  && sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' phpunit.xml \
  && sed -i 's|\./|\./web/core/|g' phpunit.xml \
  && sed -i 's|\.\./web/core/|\./web/|g' phpunit.xml \
  && mkdir -p /var/farmOS/web/sites/simpletest/browser_output

# Switch back to the /var/www/html directory as the root user.
WORKDIR /var/www/html
USER root

# Copy /var/farmOS to /opt/drupal.
RUN rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal

# Install and configure XDebug.
RUN yes | pecl install xdebug \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
