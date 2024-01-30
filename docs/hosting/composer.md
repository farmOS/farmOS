# Building farmOS with Composer

[Composer](https://getcomposer.org/) is the standard way to build and manage
PHP-based projects and their dependencies. farmOS uses Composer in its
automated build processes to generate packaged releases, including
[Docker](/hosting/install/#farmos-in-docker) images and
[tarballs](/hosting/install/#packaged-releases). These "official" releases
only include the core farmOS modules.

If you want to include additional modules that are created by yourself or
others in the community, you may find that you need more control over the
build process. This guide outlines the basic workflow for managing your
farmOS-based projects with Composer.

## Composer projects

First, it's important to understand the concept of a "Composer project".

If you are building a new PHP application from scratch with Composer, the
first step is to create a `composer.json` file. As described in the Composer
docs:

> To start using Composer in your project, all you need is a `composer.json`
> file. This file describes the dependencies of your project and may contain
> other metadata as well.

https://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup

### Project templates

Composer also provides a `create-project` command for starting a new project
from an existing one. This allows for "project templates" to be provided for
developers to build on top of.

> You can use Composer to create new projects from an existing package. This
> is the equivalent of doing a git clone/svn checkout followed by a
> `composer install` of the vendors.

https://getcomposer.org/doc/03-cli.md#create-project

A good example of this is Drupal's `recommended-project` template, which is
described as follows:

> These project templates serve as a starting point for creating a
> Composer-managed Drupal site. Once you have selected a template project and
> created your own site, you will take ownership of your new project files.
> Thereafter, future updates will be done with Composer.

https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates

For example, to create a new project based on Drupal's `recommended-project`
template:

    composer create-project drupal/recommended-project

This will initialize a `composer.json` and `composer.lock` file that serves as
a template for a Drupal website.

### farmOS project template

farmOS provides its own project template:
https://packagist.org/packages/farmOS/project

The official farmOS releases are built from this template. It can also be used
to start your own project based on farmOS. This process is described in the
next section.

## Starting your farmOS project

To start your own project based on the farmOS project template, open a new
directory and run the following command:

    composer create-project farmos/project:3.x-dev

This will create a `composer.json` file in your directory, copied directly from
the [farmOS project template](https://packagist.org/packages/farmos/project).

You can use this `composer.json` as the starting point for your own project's
code repository with [Git](https://git-scm.com/):

```shell
git init
git add composer.json
git commit -m 'Initial commit of my farmOS project.'
```

It is helpful to understand that your project is *not* farmOS, but rather
farmOS is a *dependency* of your project. You can see this clearly by observing
that `farmos/farmos` is included in your `composer.json` file's `require`
section. To customize your project, open `composer.json` in a text editor and
change the metadata (eg: `name`, `description`, `homepage`, etc).

### Building the codebase

To build your farmOS project's codebase, run the following command:

    composer install --no-dev

This will do two very important things:

1. It will download all of your project's dependencies (including farmOS) and
   build the file structure. At the end, you will have a fully-functional
   codebase. Configure your web server's document root to point to the `web`
   subdirectory, and you will be able to open farmOS in a browser.
2. It will create a `composer.lock` file that contains auto-generated
   information about your project's dependencies (and their dependencies) with
   the specific version that were downloaded. This `composer.lock` file should
   be committed to source control. It ensures that building the project again
   will always use the same versions of dependencies, for reproducible builds.

### Ignoring files

At this point there will be many files in your directory that should *not* be
committed to source control. Best practice is to ignore them with a `.gitignore` file.

Example `.gitignore`:

```
# Ignore Composer-generated files.
.editorconfig
.gitattributes
vendor
web/*

# farmOS uses this to store OAuth2 keys.
keys

# This creates an exception for ./web/modules/custom, which can be used for
# custom module code that is part of this repository.
!web/modules
web/modules/*
!web/modules/custom
```

### Community modules

farmOS community modules can be added to your project via Composer.

For example, this will add the
[farmOS Bee](https://www.drupal.org/project/farm_bee) module:

    composer require drupal/farm_bee

Notice that `composer.json` and `composer.lock` are automatically updated.
Commit these changes to ensure the module will be downloaded every time your
codebase is built.

### Custom modules

The example `.gitignore` above includes a special rule to exclude the
`./web/modules/custom` directory. This allows you to include your own custom
modules within your project and commit them to source control.

For example, create a file called
`./web/modules/custom/mymodule/mymodule.info.yml`
with the following content:

```yaml
name: My module
description: A custom module for my farmOS project.
type: module
package: farmOS Custom
core_version_requirement: ^10
```

This can be committed to your project's Git repository, and installed in your
farmOS instance via the web UI or by running `drush en mymodule`.

## Updating dependencies

Composer provides a simple way to update project dependencies:

    composer update --no-dev
    composer update --no-dev

**Note: It is necessary to run this command twice to ensure all dependencies
are properly updated.** We have an issue open to figure out a better solution:
[Composer merge plugin dependencies are not correctly updated #653](https://github.com/farmOS/farmOS/issues/653).

This will check for newer versions of all your project's dependencies (based
on the version constraints in your `composer.json` file), install them, and
update your `composer.lock` file automatically.

It is important to run automated database updates and rebuild caches whenever
farmOS and/or dependency modules are updated. For this reason, it is good to
develop a deployment strategy that includes these steps whenever a new version
of your project's code is deployed. See [Updating farmOS](/hosting/update) for
more information.

### Pinning versions

The `farmos/farmos` dependency in the farmOS project template `composer.json`
defaults to `^3.0`, which means "the latest stable version of the 3.x branch".

You may want to pin this (or other dependencies) to a specific version so that
you can be very intentional with your upgrade process.

To pin the `farmos/farmos` dependency to a specific version (eg: `3.0.1`),
replace `^3.0` with `3.0.1` and run `composer update --no-dev`.

To update pinned dependencies, simply update the version in `composer.json` and
run `composer update --no-dev` again. Remember to run automated updates and
rebuild caches after deployment. See [Updating farmOS](/hosting/update) for
more information.

## Docker

Composer is only responsible for building the farmOS codebase. After that, it
is up to you to deploy it. One way to do this is with Docker.

As described above, the "official" farmOS Docker images are built using the
default farmOS project template. You can use a similar approach to build
custom Docker images with your custom codebase.

Example `Dockerfile`:

```Dockerfile
# Inherit from the upsteam farmOS 3.x image.
FROM farmos/farmos:3.x

# Install `jq` to help in extracting the farmOS version below.
RUN apt-get update && apt-get install -y jq

# Create a fresh /var/farmOS directory.
RUN rm -r /var/farmOS && mkdir /var/farmOS

# Copy composer.json and composer.lock into the image.
COPY composer.json /var/farmOS/composer.json
COPY composer.lock /var/farmOS/composer.lock

# Build the farmOS codebase with Composer as the www-data user in /var/farmOS
# with the --no-dev flag.
RUN (cd /var/farmOS; composer install --no-dev)

# Set the version in farm.info.yml to match the version locked by Composer.
# This is optional but is useful because the version will appear as the
# "Installation Profile" version at `/admin/reports/status` in farmOS.
RUN sed -i "s|version: 3.x|version: $(jq -r '.packages[] | select(.name == "farmos/farmos").version' /var/farmOS/composer.lock)|g" /var/farmOS/web/profiles/farm/farm.info.yml

# Copy the farmOS codebase into /opt/drupal.
RUN rm -r /opt/drupal && cp -rp /var/farmOS /opt/drupal
```

With the above example `Dockerfile` in your project's root directory, the
following commands will build a custom Docker image and run it with a volume
for the `sites` directory. Note that this example is not tailored for local
development, but can be used as a basis to design something that suits your
needs.

    sudo docker build -t mycustomfarmos .
    sudo docker run --rm -it -p 80:80 -v $(pwd)/sites:/opt/drupal/web/sites mycustomfarmos

See [Hosting farmOS in Docker](/hosting/install/#farmos-in-docker) for more
information.
