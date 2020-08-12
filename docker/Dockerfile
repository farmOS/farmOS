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

# Create a directory for the farmOS codebase.
RUN mkdir /var/farmOS && chown www-data:www-data /var/farmOS

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

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod u+x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
