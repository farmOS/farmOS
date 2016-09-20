FROM drupal:7

ENV DEBIAN_FRONTEND noninteractive
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

RUN apt-get update && \
    apt-get install -y git unzip libgeos-dev

RUN php -r "readfile('http://files.drush.org/drush.phar');" > drush && \
    chmod +x drush && \
    mv drush /usr/local/bin

ADD build-farm.make /farmos/build-farm.make
ADD drupal-org-core.make /farmos/drupal-org-core.make
ADD drupal-org.make /farmos/drupal-org.make
ADD farm.info /farmos/farm.info
ADD farm.install /farmos/farm.install
ADD farm.profile  /farmos/farm.profile

WORKDIR /farmos
RUN cd /farmos && drush make build-farm.make farm
RUN rm -rf /var/www/html && \
    ln -s /farmos/farm /var/www/html && \
    chown -R www-data:www-data /farmos/farm/sites

