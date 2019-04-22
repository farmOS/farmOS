# Developing farmOS with Docker

For general information, see [Hosting farmOS with Docker].

## Docker Compose

The recommended approach for local farmOS development in Docker is to use
[Docker Compose] to run both the farmOS container and the database container
on your local host.

### Install Docker and Docker Compose

First, install Docker and Docker Compose:

* [Install Docker]
    * On Mac OS X, use "Docker for Mac" (not "Docker Toolbox")
    * On Windows, use "Docker for Windows" (not "Docker Toolbox")
    * On Linux, follow the directions on docker.com
* [Install Docker Compose]

### Create containers

To create the farmOS Docker containers, start by creating a new farmOS directory
on your host:

    mkdir farmOS
    cd farmOS

Next, copy the `docker-compose.development.yml` file into the directory and
rename it to `docker-compose.yml`:

    wget https://raw.githubusercontent.com/farmOS/farmOS/7.x-1.x/docker/docker-compose.development.yml
    mv docker-compose.development.yml docker-compose.yml

Then, use `docker-compose up` to create the containers:

    sudo docker-compose up

This will create two containers: a farmOS application container, and a MariaDB
database container.

This will run the two containers in your open terminal window, and will print
Apage and MariaDB logs to the screen. This is useful for debugging, and you can
shut them down with Ctrl+C when you're done.

If you want to run these containers in the background, so you don't need to keep
your terminal window, add `-d` to the end of the command:

    sudo docker-compose up -d

Then you can shut them down and remove the containers with:

    sudo docker-compose down

#### Mac Specific Instructions

Due to [performance issues] with shared volumes in Docker for Mac, it is
recommended that you add `:delegated` to your volume definitions in
`docker-compose.yml`.

For example, instead of:

```
volumes:
  - './db:/var/lib/mysql'
```

Replace with:

```
volumes:
  - './db:/var/lib/mysql:delegated'
```

Do this for both the `db` and `www` container volumes.

#### Persistent volumes

The `docker-compose.development.yml` file defines two Docker volumes that will
be mounted into the containers from your host directory:

* `./www` - `/var/html/www` from the farmOS application container, which
  includes the entire farmOS codebase, `settings.php` file (for connecting to
  the database), and any files that are uploaded/created in farmOS.
* `./db` - `/var/lib/mysql` from the MariaDB database container, which contains
  the farmOS database.

Both will be made available within the `farmOS` directory you created initially.

This is where you will be able to access the code for development purposes. It
is also how your database and files are persisted when the containers are
destroyed and rebuilt.

#### Backup/restore during development

During development, you can create quick snapshots of the database and/or
codebase from these volume directories. Simply shut down the running containers
and create tarball(s).

**Backup**:

    sudo docker-compose down
    tar -czf backup.tar.gz db www
    sudo docker-compose up -d

**Restore**

    sudo docker-compose down
    sudo rm -rf db
    sudo rm -rf www
    tar -xzf backup.tar.gz
    sudo docker-compose up -d

#### File ownership

On a Linux host, all the files in `www` will have an owner and group of
`www-data`. For development purposes, it is recommended that you change the
owner of everything in the `www` container to your local user. This can be done
with the following command:

    sudo chown -R ${USER} www

This changes the owner of *everything* in /var/www/html to the currently logged
in user on the host. But it leaves the group alone (`www-data`).

### Install farmOS

Once the containers are up and running, you can install farmOS using the Drupal
installer. This is a simple step-by-step process that you will need to go
through when you first access the site in your browser.

#### Browser address

If you are running Docker on Linux, you can simply go to `http://localhost` in
your browser. Otherwise, you may need to look up the IP address of the Docker
container that was created and access it that way.

To find the IP address of your farmOS container, use the following command:

    sudo docker inspect --format '{{ .NetworkSettings.Networks.farmos_default.IPAddress }}' farmos_www_1

Visit the IP address in a browser - you should see the Drupal/farmOS installer.

#### Database setup

In the "Set up database" step of installation, use the following values:

* Database name: `farm`
* Database username: `farm`
* Database password: `farm`
* Under "Advanced options", change "Database host" to: `db`

Follow the instructions to continue with the installation and you should be left
with a fully-functioning farmOS instance running in a Docker container!

### Stop/start containers

To stop your running containers:

    sudo docker-compose stop

To start your containers:

    sudo docker-compose start

### Updating farmOS

**Important**: these instructions are for updating a *development* environment
hosted with Docker. If you are running a production environment, see
[Hosting farmOS with Docker].

There are two ways to update your development codebase: incremental vs complete.

#### Incremental update

An incremental update can be done if the changes are relatively simple. This
includes commits to the farmOS repository that do not include any of the
following:

* Updates to Drupal core
* Updates to contrib modules
* New contrib modules
* New patches to Drupal core or contrib modules

These things are handled by Drush Make, which is run during a complete update
(see below). If you are familiar with Drupal and Drush Make, it is possible to
make these updates incrementally as well, but if you are not then follow the
"Complete update" instructions below.

To perform an incremental update, run `git pull origin 7.x-1.x` in the farmOS
installation profile repository, which is inside `www/profiles/farm`:

    cd www/profiles/farm
    git pull origin 7.x-1.x

#### Complete update

**Warning**: if you have made any changes to the code inside `www`, they
will be overwritten by this process. The one exception is the `www/sites`
directory, which will not be modified. It's a good idea to put extra modules
that you have downloaded/developed yourself into `www/sites/all/modules` for
this reason.

First, stop the containers and create a backup snapshot so that you can easily
restore if anything goes wrong. See "Backup/restore during development" above.

Pull the latest version of the farmOS Docker `dev` image:

    sudo docker pull farmos/farmos:dev

Stop the farmOS containers:

    sudo docker-compose down

Move the `sites` directory out of the webroot:

    sudo mv www/sites ./

Delete everthing in `www`:

    sudo rm -r www/*

Restart the farmOS containers:

    sudo docker-compose up -d

The `www` container should be automatically populated again with the new
codebase.

Restore the `sites` directory:

    sudo rm -rf www/sites
    sudo mv sites www

Run database updates by going to `/update.php` in your browser and following
the instructions.

You may also need to revert any overridden features in
`/admin/structure/features` (if they are not automatically). **Warning**: If
you have made any modifications to farmOS configuration, reverting features may
overwrite those changes.

If anything goes wrong during this process, you can restore to the backup you
created. See "Backup/restore during development" above.

[Hosting farmOS with Docker]: /hosting/docker
[Docker Compose]: https://docs.docker.com/compose
[Install Docker]: https://docs.docker.com/engine/installation
[Install Docker Compose]: https://docs.docker.com/compose/install
[performance issues]: https://forums.docker.com/t/file-access-in-mounted-volumes-extremely-slow-cpu-bound
[dlite]: https://github.com/nlf/dlite
[dlite releases]: https://github.com/nlf/dlite/releases

