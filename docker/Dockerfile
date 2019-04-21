# Inherit from the Drupal 7 image on Docker Hub.
FROM drupal:7

# Set the farmOS version in an environment variable.
ENV FARMOS_VERSION 7.x-1.x-dev

# Enable Apache rewrite module.
RUN a2enmod rewrite

# Install the BCMath PHP extension.
RUN docker-php-ext-install bcmath

# Build and install the Uploadprogress PHP extension.
# See http://git.php.net/?p=pecl/php/uploadprogress.git
RUN curl -fsSL 'http://git.php.net/?p=pecl/php/uploadprogress.git;a=snapshot;h=95d8a0fd4554e10c215d3ab301e901bd8f99c5d9;sf=tgz' -o php-uploadprogress.tar.gz \
  && tar -xzf php-uploadprogress.tar.gz \
  && rm php-uploadprogress.tar.gz \
  && ( \
    cd uploadprogress-95d8a0f \
    && phpize \
    && ./configure --enable-uploadprogress \
    && make \
    && make install \
  ) \
  && rm -r uploadprogress-95d8a0f
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
    echo 'realpath_cache_size=256K'; \
    echo 'realpath_cache_ttl=3600'; \
  } > /usr/local/etc/php/conf.d/realpath_cache-recommended.ini

# Download the packaged release of farmOS from drupal.org.
RUN curl -SL "http://ftp.drupal.org/files/projects/farm-${FARMOS_VERSION}-core.tar.gz" -o /tmp/farm-${FARMOS_VERSION}-core.tar.gz && \
  tar -xvzf /tmp/farm-${FARMOS_VERSION}-core.tar.gz -C /var/www/html/ --strip-components=1 && \
  chown -R www-data:www-data /var/www/html

# Copy the sites directory to /tmp/sites, so that it can be restored after a
# volume is mounted, if necessary.
RUN cp -rp /var/www/html/sites /tmp/sites

# Mount a volume at /var/www/html/sites.
VOLUME /var/www/html/sites

# Set the entrypoint.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod u+x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
