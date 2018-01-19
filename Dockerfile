# Inherit from the PHP 5.6 Apache image on Docker Hub.
FROM php:5.6-apache

# Set environment variables.
ENV FARMOS_VERSION 7.x-1.0-beta16
ENV FARMOS_DEV_BRANCH 7.x-1.x
ENV FARMOS_DEV false

# Enable Apache rewrite module.
RUN a2enmod rewrite

# Install the PHP extensions that Drupal needs.
RUN apt-get update && apt-get install -y libpng12-dev libjpeg-dev libpq-dev \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
  && docker-php-ext-install bcmath gd mbstring opcache pdo pdo_mysql pdo_pgsql zip

# Set recommended realpath_cache settings.
# See https://www.drupal.org/docs/7/managing-site-performance/tuning-phpini-for-drupal
RUN { \
    echo 'realpath_cache_size=256K'; \
    echo 'realpath_cache_ttl=3600'; \
  } > /usr/local/etc/php/conf.d/realpath_cache-recommended.ini

# Set recommended opcache settings.
# See https://secure.php.net/manual/en/opcache.installation.php
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=50000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
  } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install PECL Uploadprogress.
RUN pecl install uploadprogress \
  && echo 'extension=uploadprogress.so' > /usr/local/etc/php/conf.d/uploadprogress.ini

# Install git and unzip for use by Drush Make.
RUN apt-get update && apt-get install -y git unzip

# Build and install the GEOS PHP extension.
RUN apt-get update && apt-get install -y libgeos-dev bzip2 \
  && curl -fsSL 'http://download.osgeo.org/geos/geos-3.4.2.tar.bz2' -o geos.tar.bz2 \
  && mkdir -p geos \
  && tar -xf geos.tar.bz2 -C geos --strip-components=1 \
  && rm geos.tar.bz2 \
  && ( \
    cd geos \
    && ./configure --enable-php \
    && make \
    && make install \
  ) \
  && rm -r geos \
  && docker-php-ext-enable geos

# Install Drush.
RUN curl -fSL "http://files.drush.org/drush.phar" -o /usr/local/bin/drush \
  && chmod +x /usr/local/bin/drush

# Mount a volume at /var/www/html.
VOLUME /var/www/html

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
