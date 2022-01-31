# Inherit from the farmOS 2.x image.
FROM farmos/farmos:2.x

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=2.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=2.x

# Set OPcache's revalidation frequency to 0 seconds for development.
# See https://www.php.net/manual/en/opcache.configuration.php#ini.opcache.revalidate-freq
RUN sed -i 's|opcache.revalidate_freq=60|opcache.revalidate_freq=0|g' /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install and configure XDebug.
RUN yes | pecl install xdebug \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

# Change the user/group IDs of www-data inside the image to match the ID of the
# developer's user on the host machine. This allows Composer to create files
# owned by www-data inside the container, while keeping those files editable by
# the developer outside of the container.
# This defaults to 1000, based on the assumption that the developer is running
# as UID 1000 on the host machine. It can be overridden at image build time with:
# --build-arg WWW_DATA_ID=$(id -u)
ARG WWW_DATA_ID=1000
RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data

# Create a fresh /var/farmOS directory owned by www-data.
# We do this in two steps because of a known issue with Moby.
# @see https://github.com/farmOS/farmOS/pull/440
RUN rm -r /var/farmOS
RUN mkdir /var/farmOS && chown www-data:www-data /var/farmOS

# Change to the www-data user.
USER www-data

# Build the farmOS codebase in /var/farmOS.
RUN /usr/local/bin/build-farmOS.sh

# Configure PHP CodeSniffer.
RUN { \
    echo '<?xml version="1.0" encoding="UTF-8"?>'; \
    echo '<ruleset name="farmOS">'; \
    echo '  <description>PHP CodeSniffer configuration for farmOS development.</description>'; \
    echo '  <file>.</file>'; \
    echo '  <arg name="extensions" value="php,module,inc,install,test,profile,theme,css,info,txt,yml"/>'; \
    echo '  <config name="drupal_core_version" value="9"/>'; \
    echo '  <rule ref="Drupal">'; \
    echo '    <exclude name="Drupal.InfoFiles.AutoAddedKeys.Version"/>'; \
    echo '    <exclude name="Drupal.Arrays.Array.LongLineDeclaration"/>'; \
    # @todo https://www.drupal.org/project/coder/issues/2159253
    echo '    <exclude name="Drupal.Commenting.InlineComment.SpacingAfter"/>'; \
    echo '  </rule>'; \
    echo '  <rule ref="DrupalPractice">'; \
    # @todo https://www.drupal.org/project/coder/issues/2159253
    echo '    <exclude name="DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter"/>'; \
    echo '  </rule>'; \
    echo '  <rule ref="Internal.Tokenizer.Exception"><severity>0</severity></rule>'; \
    echo '</ruleset>'; \
  } > /var/farmOS/phpcs.xml \
  && /var/farmOS/vendor/bin/phpcs --config-set installed_paths /var/farmOS/vendor/drupal/coder/coder_sniffer,/var/farmOS/vendor/slevomat/coding-standard

# Configure PHPUnit.
RUN cp -p /var/farmOS/web/core/phpunit.xml.dist /var/farmOS/phpunit.xml \
  && sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' /var/farmOS/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnIncomplete="true"' /var/farmOS/phpunit.xml \
  && sed -i '/failOnWarning="true"/a \         failOnSkipped="true"' /var/farmOS/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://www"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' /var/farmOS/phpunit.xml \
  && sed -i 's|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\'''\''|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\''["chrome", { "chromeOptions": { "w3c": false, "args": ["--disable-gpu","--headless", "--no-sandbox"] } }, "http://chrome:4444/wd/hub"]'\''|g' /var/farmOS/phpunit.xml \
  && sed -i 's|\./|\./web/core/|g' /var/farmOS/phpunit.xml \
  && sed -i 's|\.\./web/core/|\./web/|g' /var/farmOS/phpunit.xml \
  && sed -i 's|  </php>|    <env name="SYMFONY_DEPRECATIONS_HELPER" value="quiet[]=indirect"/>'"\n"'  </php>|g' /var/farmOS/phpunit.xml \
  && mkdir -p /var/farmOS/web/sites/simpletest/browser_output

# Change back to the root user.
USER root

# Copy the farmOS codebase into /opt/drupal.
RUN rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal

# Create a Composer config directory for the www-data user.
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer
