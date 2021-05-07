---
title: Automated tests
---

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

## Faster testing without XDebug

The instructions above will run tests with XDebug enabled which may be helpful
for [debugging](/development/environment/debug), but is also slower. One way to avoid
XDebug is to run the tests via the prod farmOS image.

The automated tests which run upon Github check-in follow this strategy which is
orchestrated via [run-tests.yml] and a docker-compose file like
[docker-compose.testing.pgsql.yml] - corresponding files also exist for [MariaDB] and
[SQLite].

Something similar can be accomplished locally by adding an additional container to one's
dev environment using the `farmos/farmos:2.x` image and mounting the same volume from the
dev container to `/opt/drupal`.

In a docker-compose.yml based off [docker-compose.development.yml], this might look like;

```yml
  test-runner:
    image: farmos/farmos:2.x
    volumes:
      - './www:/opt/drupal'
```

The tests could then be run via `docker-compose exec` as follows;

```sh
docker-compose exec -u www-data -T test-runner phpunit --verbose --debug /opt/drupal/web/profiles/farm
```

*Note: As described in the [farmOS docker documentation](/development/environment/docker),
the dev docker container uses a different user id for the `www-data` user - by default 1000.
Since that id differs from the default `www-data` user id for the prod image - 33 - the permissions
of the files mounted to `/opt/drupal` will cause tests to fail. Solutions to this will be specific
to a developers environment, but some approaches are outlined below;*

* Build a docker image derived from `farmos/farmos:2.x` which sets the id of the `www-data` user
to match that of the dev image by including `RUN usermod -u ${WWW_DATA_ID} www-data && groupmod -g ${WWW_DATA_ID} www-data`
and passing `WWW_DATA_ID` as a build build-arg
* Before launching the tests, use `chmod`/`chown`/`setfacl` to modify the permissions of the files
mounted to `/opt/drupal` such that user id 33 can access them
* Rebuild the dev docker image to also use 33 as the user id of the `www-data` user

[run-tests.yml]: https://raw.githubusercontent.com/farmOS/farmOS/2.x/.github/workflows/run-tests.yml
[docker-compose.testing.pgsql.yml]: https://raw.githubusercontent.com/farmOS/farmOS/2.x/docker/docker-compose.testing.pgsql.yml
[MariaDB]: https://raw.githubusercontent.com/farmOS/farmOS/2.x/docker/docker-compose.testing.mariadb.yml
[SQLite]: https://raw.githubusercontent.com/farmOS/farmOS/2.x/docker/docker-compose.testing.sqlite.yml
[docker-compose.development.yml]: https://raw.githubusercontent.com/farmOS/farmOS/2.x/docker/docker-compose.development.yml

