# farmOS on Docker

> [Docker] is an open-source project that automates the deployment of applications
> inside software containers. - [Wikipedia]

farmOS comes with a [Dockerfile] that can be used to build a farmOS Docker
image. This can be used for local farmOS development purposes, or for hosting
your own live farmOS site on a web server with the help of Docker.

## Local development

The recommended approach for local farmOS development in Docker is to use
[Docker Compose] to build both the farmOS container and the MySQL database
container.

First, install Docker and Docker Compose:

* [Install Docker](https://docs.docker.com/engine/installation)
    * On Mac OS X, use "Docker for Mac" (not "Docker Toolbox")
    * On Windows, use "Docker for Windows" (not "Docker Toolbox")
    * On Linux, follow the directions on docker.com
* [Install Docker Compose](https://docs.docker.com/compose/install)

Then run the following commands to build the containers:

    git clone https://github.com/farmOS/farmOS.git
    cd farmOS
    sudo docker-compose up -d

To find the IP address of your farmOS container, use the following command:

    sudo docker inspect --format '{{ .NetworkSettings.Networks.farmos_default.IPAddress }}' farmos_farmos_1

Visit the IP address in a browser - you should see the Drupal/farmOS installer.

In the "Set up database" step of installation, use the following values:

* Database name: `farmos`
* Database username: `farmos`
* Database password: `farmos`
* Under "Advanced options", change "Database host" to: `db`

Continue with the installation and you should have a fully-functioning farmOS
instance running in a Docker container!

[Docker]: https://www.docker.com
[Wikipedia]: https://en.wikipedia.org/wiki/Docker_(software)
[Dockerfile]: https://docs.docker.com/engine/reference/builder
[Docker Compose]: https://docs.docker.com/compose

