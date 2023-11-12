# Variables are defined inside the container where this will run.
# To run it locally, setup LocalRepoPath and uncomment the following lines:
# FARMOS_PATH:=<LocalRepoPath>/docker/www
# export

phpcs:
	phpcs ${FARMOS_PATH}/web/profiles/farm

phpstan:
	phpstan analyze --memory-limit 1G ${FARMOS_PATH}/web/profiles/farm

php-compatibility:
	phpcs --standard=PHPCompatibility --runtime-set testVersion 8.2- --warning-severity=0 ${FARMOS_PATH}/web/modules
	phpcs --standard=PHPCompatibility --runtime-set testVersion 8.2- --warning-severity=0 ${FARMOS_PATH}/web/themes

phpunit-tests:
	phpunit --verbose ${FARMOS_PATH}/web/profiles/farm

drush: # u=<db-url>
	drush site-install --db-url=$(u) farm farm.modules='all'
