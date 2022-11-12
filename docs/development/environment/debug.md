# Debugging

The farmOS development Docker image comes pre-installed with
[XDebug](https://xdebug.org) 3, which allows debugger connections on port 9003.

XDebug can be configured to discover the client host automatically with the
following environment variables in `docker-compose.yml`:

    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: discover_client_host=yes

## PHPStorm

If you are using the PHPStorm IDE, an additional `XDEBUG_SESSION: PHPSTORM`
environment variable is necessary.

For example:

    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: discover_client_host=yes
      XDEBUG_SESSION: PHPSTORM

With this configuration in place, enable the "Start listening for PHP Debug
Connections" option. Add a breakpoint in your code, load the page in your
browser, and you should see a prompt appear in PHPStorm that will begin the
debugging session and pause execution at your breakpoint.

### Drush + PHPStorm

Debugging code that is run via [Drush](/development/environment/drush) commands
requires additional configuration.

The `discover_client_host=yes` configuration used above will not work when code
is executed via the command line. The Docker host IP must be set explicitly.

With the containers running, this command will print the gateway IP:

    docker inspect farmos_www_1 | grep -o '"Gateway": ".*\..*\..*\..*"'

Edit `docker-compose.yml` and set the `client_host` setting in the `XDEBUG_CONFIG`
environment variable to the gateway IP.

It is also necessary to add a `PHP_IDE_CONFIG` environment variable with a
value of `serverName=localhost`.

For example:

    environment:
      XDEBUG_MODE: debug
      XDEBUG_CONFIG: client_host=192.168.128.1
      XDEBUG_SESSION: PHPSTORM
      PHP_IDE_CONFIG: serverName=localhost

Run a `drush` command and a prompt should appear in PHPStorm. You will need to
map the path to Drush (`vendor/drush`) in the PHPStorm debugger config. Then
you can set breakpoints in the Drush code you want to test.
