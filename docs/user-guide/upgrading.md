# Upgrading Polr
-----------------
To upgrade your Polr instance to the latest `master` or to a new release, you must back up your database and files before proceeding to avoid data loss.

## Upgrading from 2.x:

- Back up your database and files
- Update your files using `git pull` or `git checkout <version_tag>` (e.g `git checkout 2.2.0`)
- Run `composer install --no-dev -o` to ensure your dependencies are up to date
- Migrate your database with `php artisan migrate` to ensure your table structure is up to date

## Upgrading from 1.x:

There are breaking changes between 2.x and 1.x, thus it is not yet possible to automatically upgrade from 1.x to 2.x.
