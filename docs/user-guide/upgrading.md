# Upgrading Polr
-----------------
To upgrade your Polr instance to the latest `master` or to a new release, you must back up your database and files before proceeding to avoid data loss.

## Upgrading from 2.x:

- Back up your database and files
- Update your files by using `git pull` or downloading a release
- Run `composer install --no-dev -o` to ensure dependencies are up to date
- Migrate database with `php artisan migrate` to ensure database structure is up to date

## Upgrading from 1.x:

There are breaking changes between 2.x and 1.x; it is not yet possible to automatically upgrade to 2.x.
