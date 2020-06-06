# Development notes

## Docker build arguments

The farmOS `dev` Docker image allows certain variables to be overridden at
image build time using the `--build-arg` parameter of `docker build`.

Available arguments and their default values are described below:

- `FARMOS_REPO` - The farmOS Git repository URL.
    - Default: `https://github.com/farmOS/farmOS.git`
- `FARMOS_VERSION` - The farmOS Git branch/tag/commit to check out.
    - Default: `2.x`
- `PROJECT_REPO` - The farmOS Composer project Git repository URL.
    - Default: `https://github.com/farmOS/composer-project.git`
- `PROJECT_VERSION` - The farmOS Composer project Git branch/tag/commit to
  check out.
    - Default: `2.x`
