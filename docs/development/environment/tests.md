# Automated tests

The farmOS development Docker image comes pre-installed with all the
dependencies necessary for running automated tests via
[PHPUnit](https://phpunit.de).

The following command will run all automated tests provided by farmOS:

```sh
docker exec -it -u www-data farmos_www_1 phpunit --verbose --debug /opt/drupal/web/profiles/farm
```

Tests from other projects/dependencies can be run in a similar fashion. For
example, the following command will run all tests in the Log module:

```sh
docker exec -it -u www-data farmos_www_1 phpunit --verbose --debug /opt/drupal/web/modules/log
```

## Chrome/Selenium Container

The PHPUnit tests depend on having Chrome/Selenium available at port 4444 and hostname "chrome".

If using a docker-compose.yml based off [docker-compose.development.yml], this can be easily achieved
by adding the following container:

```yml
  chrome:
    # Tests are failing on later versions of this image.
    # See https://github.com/farmOS/farmOS/issues/514
    image: selenium/standalone-chrome:4.1.2-20220217
```

## Faster testing without XDebug

The instructions above will run tests with XDebug enabled which may be helpful
for [debugging](/development/environment/debug), but is also slower. XDebug can be disabled
by setting the `XDEBUG_MODE` environment variable to "off".

In a docker-compose.yml based off [docker-compose.development.yml], this might look like:

```yml
  www:
    ...
    environment:
      ...
      XDEBUG_MODE: 'off'
```

The tests could then be run via `docker compose exec` as follows:

```sh
docker compose exec -u www-data -T www phpunit --verbose --debug /opt/drupal/web/profiles/farm
```

Alternatively, the `XDEBUG_MODE` environment variable can be specified directly:

```sh
docker compose exec -u www-data -T --env XDEBUG_MODE=off www phpunit --verbose --debug /opt/drupal/web/profiles/farm
```

[run-tests.yml]: https://raw.githubusercontent.com/farmOS/farmOS/3.x/.github/workflows/run-tests.yml
[docker-compose.development.yml]: https://raw.githubusercontent.com/farmOS/farmOS/3.x/docker/docker-compose.development.yml
