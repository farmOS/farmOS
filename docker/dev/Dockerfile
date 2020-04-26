# Inherit from the farmOS 2.0.x base image.
FROM farmos/farmos:2.0.x-base

# Install git and unzip (needed by Composer).
RUN apt-get update -y \
  && apt-get install -y git unzip

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Generate an empty project from farmos/project.
RUN composer create-project --stability=dev farmos/project /var/www/farmOS

# Change ownership of /var/www/farmOS to www-data:www-data and copy it into
# /var/www/html.
RUN chown -R www-data:www-data /var/www/farmOS \
  && rm -r /var/www/html \
  && cp -r /var/www/farmOS /var/www/html

# Install and configure XDebug.
RUN yes | pecl install xdebug \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
