# Inherit from the PHP 5.6 Apache image on Docker Hub.
FROM php:5.6-apache

# Enable Apache rewrite module.
RUN a2enmod rewrite

# Install the PHP extensions that Drupal needs.
RUN apt-get update && apt-get install -y libpng12-dev libjpeg-dev libpq-dev \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
  && docker-php-ext-install bcmath gd mbstring opcache pdo pdo_mysql pdo_pgsql zip

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

# Install other dependencies via apt-get.
RUN apt-get update && apt-get install -y \
  bzip2 \
  git \
  php5-geos \
  phpunit \
  unzip

# Build and install the GEOS PHP extension.
RUN curl -fsSL 'http://download.osgeo.org/geos/geos-3.4.2.tar.bz2' -o geos.tar.bz2 \
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

# Set environment variables.
ENV FARMOS_VERSION 7.x-1.0-beta12
ENV FARMOS_DEV_BRANCH 7.x-1.x
ENV FARMOS_DEV false

# Mount a volume at /var/www/html.
VOLUME /var/www/html

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

