# Inherit from the PHP 5.6 Apache image on Docker Hub.
FROM php:5.6-apache

# Install dependencies via apt-get.
RUN apt-get update && apt-get install -y \
  git \
  libgeos-dev \
  unzip

# Install Drush.
RUN curl -fSL "http://files.drush.org/drush.phar" -o /usr/local/bin/drush \
  && chmod +x /usr/local/bin/drush

# Build farmOS with Drush Make.
COPY build-farm.make /farmOS/build-farm.make
COPY drupal-org-core.make /farmOS/drupal-org-core.make
WORKDIR /farmOS
RUN cd /farmOS && drush make build-farm.make farm

# Replace /var/www/html with farmOS.
RUN rm -rf /var/www/html && ln -s /farmOS/farm /var/www/html

# Change ownership of the Drupal sites folder to www-data.
RUN chown -R www-data:www-data /farmOS/farm/sites

