# Getting started

Follow these instructions to set up a local farmOS development environment.

The only requirements are [Docker](https://www.docker.com) and
[Docker Compose](https://docs.docker.com/compose).

## 1. Set up Docker containers

Run the following commands to create a farmOS directory and set up Docker
containers for farmOS and PostgreSQL:

    mkdir farmOS && cd farmOS
    curl https://raw.githubusercontent.com/farmOS/farmOS/2.x/docker/docker-compose.development.yml -o docker-compose.yml
    docker compose up -d

## 2. Install farmOS

Open `http://localhost` in a browser and install farmOS with the following
database credentials:

- Database type: **PostgreSQL**
- Database name: `farm`
- Database user: `farm`
- Database password: `farm`
- Advanced options > Host: `db`

## 3. Develop

Open the `www` directory in your favorite IDE.

## Optional

### Configure private filesystem

In order to upload files, a private file path must be configured. The following
line must be added to `www/web/sites/default/settings.php`:

    $settings['file_private_path'] = '/opt/drupal/web/sites/default/private/files';

Additionally, create the folder `/opt/drupal/web/sites/default/private/`.

Set the correct user and permissions:

Folder ownership and group should match the web server user. If you are using
the farmOS Docker image (running Apache), this will be `www-data`.

Folder permissions should be set to `770` or `drwxrwx---`.

Finally, make sure to clear the caches by visiting Administration >
Configuration > Development > Performance and clicking the `Clear all caches`
button, or use Drush via the command line: `drush cr`.

### Configure debugger

See [Debugging](/development/environment/debug).

### Enable HTTPS

See [HTTPS](/development/environment/https).
