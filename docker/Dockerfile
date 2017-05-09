# Inherit from the PHP 7.0 Apache image on Docker Hub.
FROM php:7.0-apache

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
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install other dependencies via apt-get.
RUN apt-get update && apt-get install -y \
  bzip2 \
  git \
  phpunit \
  unzip

# Install Drush.
RUN curl -fSL "http://files.drush.org/drush.phar" -o /usr/local/bin/drush \
  && chmod +x /usr/local/bin/drush

# Set environment variables.
ENV FARMOS_VERSION 8.x-2.x-dev
ENV FARMOS_DEV_BRANCH 8.x-2.x
ENV FARMOS_DEV false

# Mount a volume at /var/www/html.
VOLUME /var/www/html

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
