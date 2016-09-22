FROM drupal:7

ENV DEBIAN_FRONTEND noninteractive
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

RUN apt-get update && \
    apt-get install -y git unzip libgeos-dev

RUN php -r "readfile('http://files.drush.org/drush.phar');" > drush && \
    chmod +x drush && \
    mv drush /usr/local/bin

ADD build-farm.make /farmOS/build-farm.make
ADD drupal-org-core.make /farmOS/drupal-org-core.make

WORKDIR /farmOS
RUN cd /farmOS && drush make build-farm.make farm
RUN rm -rf /var/www/html && \
    ln -s /farmOS/farm /var/www/html && \
    chown -R www-data:www-data /farmOS/farm/sites

