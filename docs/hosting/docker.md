# Hosting farmOS with Docker

> [Docker] is an open-source project that automates the deployment of
> applications inside software containers. - [Wikipedia]

A farmOS Docker image is available on [Docker Hub] at
[https://hub.docker.com/r/farmos/farmos](https://hub.docker.com/r/farmos/farmos).
This image contains the farmOS codebase with all dependencies.

It does not include the database server, but that can be set up separately as a
standalone service, or in another Docker container.

## Development environment

The farmOS Docker image can be used in both development and production
environments. However, a separate `dev` Docker image is available for
development purposes. See [Developing farmOS with Docker] for instructions on
setting up a development environment.

## Production environment

If you plan to host farmOS in a production environment with Docker, it is
assumed that you have experience with Docker in production already and will make
decisions about how to fit it into your specific server environment. An example
`docker-compose.production.yml` file is provided in the farmOS repository's
`docker` directory which demonstrates the basics. You can copy and rename this
to `docker-compose.yml` as a starting point.

When new versions of farmOS are released, they are tagged on Docker hub. The
`latest` tag is also used to point to the most recent stable release tag,
although using this to automatically update is not recommended unless you also
have a plan for running `update.php` with each update.

### Updating farmOS

General instructions for updating to a new version of farmOS are described in
the [Updating farmOS] docs. It is important to familiarize yourself with that
process before considerring how to do it with Docker.

Updating farmOS hosted with Docker (assuming that you are using the example
`docker-compose.production.yml` configuration as an example) is roughly the
same process, with a few exceptions:

* Steps 2 and 3 (downloading and unpacking the new version) are performed
  automatically during the Docker image build process.
* The farmOS codebase is built into the Docker image in `/var/www/html`, and
  the site-specific settings and files are mounted in as a volume in
  `/var/www/html/sites`, so they persist outside of the container.
* Drush is not installed in the farmOS Docker image. You can create a derivative
  image that includes Drush, if necessary. The farmOS development image does
  this, so you can look at `docker/dev/Dockerfile` in the farmOS repository as
  an example.

With `/var/www/html/sites` mounted as a volume, you can simply update to a new
version of the farmOS Docker image itself and then run `update.php` to update
farmOS.

[Docker]: https://www.docker.com
[Wikipedia]: https://en.wikipedia.org/wiki/Docker_(software)
[Docker Hub]: https://hub.docker.com
[Developing farmOS with Docker]: /development/docker
[Updating farmOS]: /hosting/updating

