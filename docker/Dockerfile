# Inherit from the Drupal 10 image on Docker Hub.
FROM drupal:10.1

# Set the farmOS and composer project repository URLs and versions.
ARG FARMOS_REPO=https://github.com/farmOS/farmOS.git
ARG FARMOS_VERSION=3.x
ARG PROJECT_REPO=https://github.com/farmOS/composer-project.git
ARG PROJECT_VERSION=3.x

# Set Apache ServerName directive globally to suppress AH00558 message.
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install the BCMath PHP extension.
RUN docker-php-ext-install bcmath

# Install git and unzip (needed to build PHP GEOS and needed by Composer).
RUN apt-get update \
  && apt-get install -y git unzip

# Build and install the GEOS PHP extension.
# See https://git.osgeo.org/gitea/geos/php-geos
RUN apt-get update && apt-get install -y libgeos-dev \
  && git clone https://github.com/libgeos/php-geos.git \
  && ( \
    cd php-geos \
    # Checkout latest commit with PHP 8 support.
    && git checkout e77d5a16abbf89a59d947d1fe49381a944762c9d \
    && ./autogen.sh \
    && ./configure \
    && make \
    && make install \
  ) \
  && rm -r php-geos \
  && docker-php-ext-enable geos

# Add custom PHP configurations.
COPY conf.d/ /usr/local/etc/php/conf.d

# Install postgresql-client so Drush can connect to the database.
RUN apt-get update && apt-get install -y postgresql-client

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
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
