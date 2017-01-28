# Docker

farmOS can be installed locally with [Docker](https://www.docker.com).

Requirements:

* Docker
* Docker Compose

Run the following command from the farmOS repository's root directory:

    sudo docker-compose up

Then go to `http://localhost` in your browser to complete installation.

When prompted for database credentials, use the following:

* Name: `farm`
* User: `farm`
* Password: `farm`
* Hostname (under "Advanced"): `db`

Known issues:

* Email does not work from within the Docker container.
