# Automated tests

The farmOS development Docker image comes pre-installed with all the
dependencies necessary for running automated tests via
[PHPUnit](https://phpunit.de).

The following command will run all automated tests provided by farmOS:

    sudo docker exec -it -u www-data farmos_www_1 phpunit --verbose --debug --group farm

Tests from other projects/dependencies can be run in a similar fashion. For
example, the following command will run all tests in the Log module:

    sudo docker exec -it -u www-data farmos_www_1 phpunit --verbose --debug --group Log
