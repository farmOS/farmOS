# Inherit from the Drupal 9 image on Docker Hub.
FROM drupal:9

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=2.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=2.x

# Install the BCMath PHP extension.
RUN docker-php-ext-install bcmath

# Build and install the Uploadprogress PHP extension.
# See http://git.php.net/?p=pecl/php/uploadprogress.git
RUN curl -fsSL 'https://git.php.net/?p=pecl/php/uploadprogress.git;a=snapshot;h=be300078bbd457d341753a9fa7edf6b4a95c9765;sf=tgz' -o php-uploadprogress.tar.gz \
  && tar -xzf php-uploadprogress.tar.gz \
  && rm php-uploadprogress.tar.gz \
  && ( \
    cd uploadprogress-be30007 \
    && phpize \
    && ./configure --enable-uploadprogress \
    && make \
    && make install \
  ) \
  && rm -r uploadprogress-be30007 \
  && docker-php-ext-enable uploadprogress

# Build and install the GEOS PHP extension.
# See https://git.osgeo.org/gitea/geos/php-geos
RUN apt-get update && apt-get install -y libgeos-dev \
  && curl -fsSL 'https://git.osgeo.org/gitea/geos/php-geos/archive/1.0.0.tar.gz' -o php-geos.tar.gz \
  && tar -xzf php-geos.tar.gz \
  && rm php-geos.tar.gz \
  && ( \
    cd php-geos \
    && ./autogen.sh \
    && ./configure \
    && make \
    && make install \
  ) \
  && rm -r php-geos \
  && docker-php-ext-enable geos

# Set recommended PHP settings for farmOS.
# See https://farmos.org/hosting/installing/#requirements
RUN { \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=240'; \
    echo 'max_input_time=240'; \
    echo 'max_input_vars=5000'; \
  } > /usr/local/etc/php/conf.d/farmOS-recommended.ini

# Set recommended realpath_cache settings.
# See https://www.drupal.org/docs/7/managing-site-performance/tuning-phpini-for-drupal
RUN { \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=3600'; \
  } > /usr/local/etc/php/conf.d/realpath_cache-recommended.ini

# Install postgresql-client so Drush can connect to the database.
RUN apt-get update \
  # See https://stackoverflow.com/questions/51033689/how-to-fix-error-on-postgres-install-ubuntu
  && mkdir -p /usr/share/man/man1 \
  && mkdir -p /usr/share/man/man7 \
  && apt-get install -y postgresql-client

# Install git and unzip (needed by Composer).
RUN apt-get update \
  && apt-get install -y git unzip

# Set the COMPOSER_MEMORY_LIMIT environment variable to unlimited.
ENV COMPOSER_MEMORY_LIMIT=-1

# Add the build-farmOS.sh script.
COPY build-farmOS.sh /usr/local/bin/
RUN chmod a+x /usr/local/bin/build-farmOS.sh

# Build the farmOS codebase in /var/farmoS with the --no-dev flag.
# Change the ownership of the sites directory and copy the farmOS codebase into /opt/drupal.
RUN mkdir /var/farmOS \
  && /usr/local/bin/build-farmOS.sh --no-dev \
  && chown -R www-data:www-data /var/farmOS/web/sites \
  && rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod u+x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
