---
title: Debugging
---

# Debugging

The farmOS development Docker image comes pre-installed with
[XDebug](https://xdebug.org) 3, which allows debugger connections on port 9003.

In order to connect to it, the `XDEBUG_CONFIG` environment variable must be
used to configure XDebug's `client_host` setting  with the Docker container's
"Gateway" IP address.

With the containers running, this command will print the gateway IP:

    docker inspect farmos_www_1 | grep -o '"Gateway": ".*\..*\..*\..*"'

Edit `docker-compose.yml` and enter the gateway IP in the `XDEBUG_CONFIG`
environment variable. For example:

    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: client_host=192.168.128.1

Restart the Docker containers for this change to take affect.

    docker-compose restart

**Note**: If the Docker containers are removed and recreated, the IP address
may change, and you will need to repeat these steps to reconfigure it.

## PHPStorm

If you are using the PHPStorm IDE, with the configuration above in place,
enable the "Start listening for PHP Debug Connections" option. Add a
breakpoint in your code, load the page in your browser, and you should see
a prompt appear in PHPStorm that will begin the debugging session and pause
execution at your breakpoint.

### Drush + PHPStorm

Debugging code that is run via [Drush](/development/environment/drush) commands
requires additional configuration. Add an `XDEBUG_SESSION` environment variable
with a value of `PHPSTORM`, and a `PHP_IDE_CONFIG` environment variable with a
value of `serverName=localhost`, as follows:

    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: client_host=192.168.128.1
      XDEBUG_SESSION: PHPSTORM
      PHP_IDE_CONFIG: serverName=localhost

Run a `drush` command and a prompt should appear in PHPStorm. You will need to
map the path to Drush (`vendor/drush`) in the PHPStorm debugger config. Then
you can set breakpoints in the Drush code you want to test.
