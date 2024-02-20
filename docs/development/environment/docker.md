# Docker

## Docker build arguments

The farmOS Docker images allow certain variables to be overridden at
image build time using the `--build-arg` parameter of `docker build`.

Available arguments and their default values are described below:

- `FARMOS_REPO` - The farmOS Git repository URL.
    - Default: `https://github.com/farmOS/farmOS.git`
- `FARMOS_VERSION` - The farmOS Git branch/tag/commit to check out.
    - Default: `3.x`
- `PROJECT_REPO` - The farmOS Composer project Git repository URL.
    - Default: `https://github.com/farmOS/composer-project.git`
- `PROJECT_VERSION` - The farmOS Composer project Git branch/tag/commit to
  check out.
    - Default: `3.x`

## Development image

The `3.x-dev` image also provides the following build arguments:

- `WWW_DATA_ID` - The ID to use for the `www-data` user and group inside the
   image. Setting this to the ID of the developer's user on the host machine
   allows Composer to create files owned by www-data inside the container,
   while keeping those files editable by the developer outside of the
   container. If your user ID is not `1000`, build the image with:
   `--build-arg WWW_DATA_ID=$(id -u)`
    - Default: `1000`

To build the development image, you will have to define the target dev,
for example:

`docker build --build-arg WWW_DATA_ID=$(id -u)  -t farmos/farmos:3.x-dev --target dev docker`
