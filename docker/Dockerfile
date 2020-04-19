# Inherit from the Drupal 8 image on Docker Hub.
FROM drupal:8

# Enable Apache rewrite module.
RUN a2enmod rewrite

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
  && rm -r uploadprogress-be30007
RUN docker-php-ext-enable uploadprogress

# Build and install the GEOS PHP extension.
# See https://git.osgeo.org/gitea/geos/php-geos
RUN apt-get update && apt-get install -y libgeos-dev
RUN curl -fsSL 'https://git.osgeo.org/gitea/geos/php-geos/archive/1.0.0.tar.gz' -o php-geos.tar.gz \
  && tar -xzf php-geos.tar.gz \
  && rm php-geos.tar.gz \
  && ( \
    cd php-geos \
    && ./autogen.sh \
    && ./configure \
    && make \
    && make install \
  ) \
  && rm -r php-geos
RUN docker-php-ext-enable geos

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

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Install git and unzip (needed by Composer).
RUN apt-get update -y \
  && apt-get install -y git unzip

# Generate an empty project from farmos/project.
RUN composer create-project --stability=dev farmos/project /var/www/farmOS

# Change ownership of /var/www/farmOS to www-data:www-data and copy it into
# /var/www/html.
RUN chown -R www-data:www-data /var/www/farmOS \
  && rm -r /var/www/html \
  && cp -r /var/www/farmOS /var/www/html

# Configure Apache to use /var/www/html/web as the document root.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
  && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the sites directory to /tmp/sites, so that it can be restored after a
# volume is mounted, if necessary.
RUN cp -rp /var/www/html/web/sites /tmp/sites

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod u+x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
