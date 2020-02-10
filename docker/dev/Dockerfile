# Inherit from the farmOS 7.x-1.x image.
FROM farmos/farmos:7.x-1.x

# Set the farmOS version to the development branch.
ENV FARMOS_VERSION 7.x-1.x

# Install Xdebug.
RUN yes | pecl install xdebug \
	  && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install git and unzip for use by Drush Make.
RUN apt-get update && apt-get install -y git unzip

# Install Drush 8 with the phar file.
ENV DRUSH_VERSION 8.2.1
RUN curl -fsSL -o /usr/local/bin/drush.phar "https://github.com/drush-ops/drush/releases/download/${DRUSH_VERSION}/drush.phar" \
  && chmod +x /usr/local/bin/drush.phar \
  && ln -s /usr/local/bin/drush.phar /usr/local/bin/drush \
  && drush core-status

# Install mariadb-client so Drush can connect to the database.
RUN apt-get update && apt-get install -y mariadb-client

# Build the farmOS repository in /tmp/farmOS.
RUN git clone --branch ${FARMOS_VERSION} https://git.drupal.org/project/farm.git /tmp/farmOS && \
  drush make --working-copy --no-gitinfofile /tmp/farmOS/build-farm.make /tmp/www && \
  chown -R www-data:www-data /tmp/www

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod u+x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
