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

[Docker]: https://www.docker.com
[Wikipedia]: https://en.wikipedia.org/wiki/Docker_(software)
[Docker Hub]: https://hub.docker.com
[Developing farmOS with Docker]: /development/docker

