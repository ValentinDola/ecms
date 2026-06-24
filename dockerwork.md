# Docker for ECMS

This document explains how Docker is used with this Laravel-based ECMS project and the main benefits and commands for development and CI.

**Why we use Docker**
- Consistent environment: same PHP, extensions, Composer, Node, and CLI tools across all developers and CI.
- Isolation: services (PHP, DB, Redis, queue workers) run in containers and don't pollute the host.
- Easy onboarding: developers run a few commands to get the full stack running.
- Reproducible builds: images can be pinned to exact versions for production parity.

**Typical services**
- php (PHP runtime, Composer, Artisan)
- web (Nginx or Apache) — often combined with `php` in dev using `php-fpm` + `nginx`
- db (MySQL / MariaDB / Postgres)
- redis (optional)
- node (optional — for building assets)

Example minimal `docker-compose.yml` (conceptual)

```yaml
version: '3.8'
services:
  app:
    build: ./docker/php
    volumes:
      - ./:/var/www/html
    environment:
      - APP_ENV=local
    ports:
      - "9000:9000" # if using php-fpm directly for debugging
    depends_on:
      - db

  web:
    image: nginx:stable-alpine
    volumes:
      - ./:/var/www/html:ro
      - ./docker/nginx/conf.d:/etc/nginx/conf.d:ro
    ports:
      - "8080:80"
    depends_on:
      - app

  db:
    image: mysql:8
    environment:
      MYSQL_DATABASE: ecms
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - ecms_db:/var/lib/mysql

volumes:
  ecms_db:
```

(Adjust images, ports and env values per your environment and security policies.)

**Common developer commands**
- Build images and start services:

```bash
docker-compose up -d --build
```

- Run Composer install inside the `app` container:

```bash
docker-compose exec app composer install --no-interaction --prefer-dist
```

- Run NPM/Yarn build (if using node inside a container):

```bash
docker-compose exec node npm ci
docker-compose exec node npm run build
```

- Run migrations and seeders:

```bash
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --class=DatabaseSeeder
```

- Run artisan commands / tinker / tests:

```bash
docker-compose exec app php artisan <command>
# Run PHPUnit
docker-compose exec app vendor/bin/phpunit
```

- Access a shell in the app container:

```bash
docker-compose exec app bash
# or sh
```

**Development notes & tips**
- Bind mounts (`.:/var/www/html`) enable live code editing. For speed on macOS/Windows, consider using cached mounts or named volumes for vendor directories.
- Keep persistent data in Docker named volumes (DB, storage) so you don't lose data on container recreate.
- Use environment variables from `.env` — do not commit secrets. Use a `.env.docker` for container-specific overrides and map it to the container environment in `docker-compose.yml`.
- For route/model binding changes (like switching to UUID `ref_no`), run migrations and seeders inside the container rather than on host PHP to avoid environment differences.
- If PHP is not installed on the host (common on Windows), Docker provides a portable environment to run `php artisan` and `vendor/bin/phpunit`.

**CI and Production**
- CI pipelines should build the app image and run tests inside a container using the same base image as development.
- For production, build a lean image (no dev tools), compile assets during the build stage, and use environment-specific secrets and managed databases.

**Troubleshooting**
- Permission issues for `storage` and `bootstrap/cache`: set proper UID/GID in the Dockerfile or `chown` at container startup.
- If `php` is not found on host, use `docker-compose exec app php -v` for diagnostics.
- Rebuild when changing Dockerfile(s): `docker-compose build --no-cache app` then `docker-compose up -d`.

**Summary**
Docker ensures consistent, isolated, and reproducible environments for ECMS. Use the `app` service for PHP/Artisan/Composer work, `db` for persistent data, and `web` for serving HTTP in dev. Follow the commands above to build, migrate, seed, and test inside containers.

If you want, I can add a concrete `docker/` folder with a reference `Dockerfile` for `app` and a ready `docker-compose.yml` tuned for this project.
