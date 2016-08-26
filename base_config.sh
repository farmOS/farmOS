#!/bin/bash
mkdir -p /config/apache/site-confs /config/www /config/log/apache /config/keys

PUID=${PUID:-911}
PGID=${PGID:-911}

if [ ! "$(id -u abc)" -eq "$PUID" ]; then usermod -o -u "$PUID" abc ; fi
if [ ! "$(id -g abc)" -eq "$PGID" ]; then groupmod -o -g "$PGID" abc ; fi

if [ ! -f "/config/apache/apache2.conf" ]; then
cp /defaults/apache2.conf /config/apache/apache2.conf
fi
cp config/apache/apache2.conf /etc/apache2/apache2.conf

if [ ! -f "/config/apache/ports.conf" ]; then
cp /defaults/ports.conf /config/apache/ports.conf
fi

if [ ! -f "/config/apache/site-confs/default.conf" ]; then
cp /defaults/default.conf /config/apache/site-confs/default.conf
fi

if [[ $(find /config/www -type f | wc -l) -eq 0 ]]; then
cp -R /defaults/farmos/* /config/www
fi

chown -R abc:abc /config
chown -R abc:abc /var/lib/apache2
