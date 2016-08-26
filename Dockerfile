FROM thedude459/farmos-baseimage:latest
MAINTAINER thedude459

# install main packages
RUN apt-get update 
RUN apt-get install -y

# cleanup 
RUN apt-get clean -y && \
rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# replace base_config
RUN rm -rf /etc/my_init.d/base_config.sh
ADD base_config.sh /etc/my_init.d/base_config.sh

# permissions
RUN chmod -v +x /etc/service/*/run /etc/my_init.d/*.sh

# install farmOS
ADD build-farm.make /var/tmp/farmos/build-farm.make
ADD drupal-org-core.make /var/tmp/farmos/drupal-org-core.make  
ADD drupal-org.make /var/tmp/farmos/drupal-org.make  
ADD farm.info /var/tmp/farmos/farm.info  
ADD farm.install /var/tmp/farmos/farm.install  
ADD farm.profile  /var/tmp/farmos/farm.profile

RUN cd /var/tmp/farmos && drush make build-farm.make farm 

# move farmOS
RUN rm -rf /var/www/html
RUN cp -R /var/tmp/farmos/farm /defaults/farmos
RUN rm -rf /var/tmp/farmos

# ports and volumes
EXPOSE 80 443
VOLUME /config


