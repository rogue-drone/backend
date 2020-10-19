# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework, with full [HTTP/2](https://symfony.com/doc/current/weblink.html) and HTTPS support.

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. Run `docker-compose up` (the logs will be displayed in the current shell)
2. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
3. **Enjoy!**

## Features

* Production, development and CI ready
* Automatic HTTPS (in dev and in prod!)
* HTTP/2, HTTP/3 and [Server Push](https://symfony.com/doc/current/web_link.html) support
* [Vulcain](https://vulcain.rocks)-enabled
* Just 2 services (PHP FPM and Caddy server)
* Super-readable configuration

## Selecting a Specific Symfony Version

Use the `SYMFONY_VERSION` environment variable to select a specific Symfony version.

For instance, use the following command to install Symfony 4.4:

    $ SYMFONY_VERSION=4.4.* docker-compose up --build

To install a non-stable version of Symfony, use the `STABILITY` environment variable during the build.
The value must be [a valid Composer stability option](https://getcomposer.org/doc/04-schema.md#minimum-stability)) .

For instance, use the following command to use the `master` branch of Symfony:

    $ STABILITY=dev docker-compose up --build

## Customize Server Name

Use the `SERVER_NAME` environment variable to define your custom server name.

    $ SERVER_NAME=symfony.wip docker-compose up --build

## Debugging

The default Docker stack is shipped without a Xdebug stage.
It's easy though to add [Xdebug](https://xdebug.org/) to your project, for development purposes such as debugging tests or API requests remotely.

### Add a Development Stage to the Dockerfile

To avoid deploying Symfony Docker to production with an active Xdebug extension,
it's recommended to add a custom stage to the end of the `Dockerfile`.

```Dockerfile
# Dockerfile
FROM symfony_php as symfony_php_dev

ARG XDEBUG_VERSION=2.9.8
RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps
```

### Configure Xdebug with Docker Compose Override

Using an [override](https://docs.docker.com/compose/reference/overview/#specifying-multiple-compose-files) file named `docker-compose.debug.yaml` ensures that the production
configuration remains untouched.

As example, an override could look like this:

```yaml
# docker-compose.debug.yaml
version: "3.4"

services:
  php:
    build:
      context: .
      target: symfony_php_dev
    environment:
      # See https://docs.docker.com/docker-for-mac/networking/#i-want-to-connect-from-a-container-to-a-service-on-the-host
      # See https://github.com/docker/for-linux/issues/264
      # The `remote_host` below may optionally be replaced with `remote_connect_back`
      XDEBUG_CONFIG: >-
        remote_enable=1
        remote_host=host.docker.internal
        remote_port=9001
        idekey=PHPSTORM
      # This should correspond to the server declared in PHPStorm `Preferences | Languages & Frameworks | PHP | Servers`
      # Then PHPStorm will use the corresponding path mappings
      PHP_IDE_CONFIG: serverName=symfony
```

Then run:

    $ docker-compose -f docker-compose.yml -f docker-compose.debug.yml up -d

### Troubleshooting

Inspect the installation with the following command. The requested Xdebug version should be displayed in the output.

    $ docker-compose exec php php --version
    
    PHP ...
        with Xdebug v2.8.0 ...

### Editing Permissions on Linux

If you work on linux and cannot edit some of the project files right after the first installation, you can run `docker-compose run --rm php chown -R $(id -u):$(id -g) .` to set yourself as owner of the project files that were created by the docker container.

### Fix Chrome/Brave SSL

If you have a SSL trust issues, download the self-signed certificate and run :

    $ sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /path/to/you/certificate.cer

## Credits

Created by [Kévin Dunglas](https://dunglas.fr), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
