# Inherit from the farmOS 2.x image.
FROM farmos/farmos:2.x

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
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data

# Install and configure XDebug.
RUN yes | pecl install xdebug \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Create a fresh /var/farmOS directory owned by www-data.
RUN rm -r /var/farmOS && mkdir /var/farmOS && chown www-data:www-data /var/farmOS

# Change to the www-data user.
USER www-data

# Build the farmOS codebase in /var/farmOS.
RUN /usr/local/bin/build-farmOS.sh

# Configure PHPUnit.
RUN cp -p /var/farmOS/web/core/phpunit.xml.dist /var/farmOS/phpunit.xml \
  && sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://localhost"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|\./|\./web/core/|g' /var/farmOS/phpunit.xml \
  && sed -i 's|\.\./web/core/|\./web/|g' /var/farmOS/phpunit.xml \
  && mkdir -p /var/farmOS/web/sites/simpletest/browser_output

# Change back to the root user.
USER root

# Copy the farmOS codebase into /opt/drupal.
RUN rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal
