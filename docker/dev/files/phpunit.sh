#!/usr/bin/env sh

cp -p ${FARMOS_PATH}/web/core/phpunit.xml.dist ${FARMOS_PATH}/phpunit.xml
sed -i 's|bootstrap="tests/bootstrap.php"|bootstrap="web/core/tests/bootstrap.php"|g' ${FARMOS_PATH}/phpunit.xml
sed -i '/failOnWarning="true"/a \         failOnIncomplete="true"' ${FARMOS_PATH}/phpunit.xml
sed -i '/failOnWarning="true"/a \         failOnSkipped="true"' ${FARMOS_PATH}/phpunit.xml
sed -i 's|name="SIMPLETEST_BASE_URL" value=""|name="SIMPLETEST_BASE_URL" value="http://www"|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|name="SIMPLETEST_DB" value=""|name="SIMPLETEST_DB" value="pgsql://farm:farm@db/farm"|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|name="BROWSERTEST_OUTPUT_DIRECTORY" value=""|name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/sites/simpletest/browser_output"|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\'''\''|name="MINK_DRIVER_ARGS_WEBDRIVER" value='\''["chrome", { "chromeOptions": { "w3c": false, "args": ["--disable-gpu","--headless", "--no-sandbox"] } }, "http://chrome:4444/wd/hub"]'\''|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|\./|\./web/core/|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|\.\./web/core/|\./web/|g' ${FARMOS_PATH}/phpunit.xml
sed -i 's|  </php>|    <env name="SYMFONY_DEPRECATIONS_HELPER" value="disabled"/>'"\n"'  </php>|g' ${FARMOS_PATH}/phpunit.xml

# Create output directory for phpunit tests and permissions for testing user.
mkdir -p ${FARMOS_PATH}/web/sites/simpletest/browser_output
chown -R www-data:www-data ${FARMOS_PATH}/web/sites/simpletest

rm ${FARMOS_PATH}/phpunit.sh
