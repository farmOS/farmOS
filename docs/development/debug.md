# Debugging

The farmOS development Docker image comes pre-installed with
[XDebug](https://xdebug.org). In order to connect to it, the `XDEBUG_CONFIG`
environment variable must be used to configure XDebug's `remote_host` setting
with the Docker container's "Gateway" IP address.

With the containers running, this command will print the gateway IP:

    sudo docker inspect farmos_www_1 | grep -o '"Gateway": ".*\..*\..*\..*"'

Edit `docker-compose.yml` and enter the gateway IP in the `XDEBUG_CONFIG`
environment variable. For example:

    environment:
      XDEBUG_CONFIG: remote_host=192.168.128.1

Restart the Docker containers for this change to take affect.

    sudo docker-compose restart

**Note**: If the Docker containers are removed and recreated, the IP address
may change, and you will need to repeat these steps to reconfigure it.
