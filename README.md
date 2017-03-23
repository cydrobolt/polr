# [![Logo](http://i.imgur.com/aOtrJNz.png)](https://project.polr.me)

:aerial_tramway: A modern, minimalist, and lightweight URL shortener.

[![GitHub license](https://img.shields.io/badge/license-GPLv2%2B-blue.svg)]()
[![GitHub release](https://img.shields.io/github/release/cydrobolt/polr.svg)](https://github.com/cydrobolt/polr/releases)
[![Builds status](https://travis-ci.org/cydrobolt/polr.svg)](https://travis-ci.org/cydrobolt/polr) 
[![Docs](https://img.shields.io/badge/docs-latest-brightgreen.svg?style=flat)](http://polr.readthedocs.org/en/latest/)


Polr is an intrepid, self-hostable open-source link shortening web application with a robust API. It allows you to host your own URL shortener, to brand your URLs, and to gain control over your data. Polr is especially easy to use, and provides a modern, themable feel.

[Getting Started](http://docs.polr.me/en/latest/user-guide/installation/) - [API Documentation](http://docs.polr.me/en/latest/developer-guide/api/) - [Contributing](https://github.com/cydrobolt/polr/blob/master/.github/CONTRIBUTING.md) - [Bugs](https://github.com/cydrobolt/polr/issues) - [IRC](http://webchat.freenode.net/?channels=#polr)

### Quickstart

Polr is written in PHP and Lumen, using MySQL as its primary database.

 - To get started with Polr on your server, check out the [installation guide](http://docs.polr.me/en/latest/user-guide/installation/). You can clone this repository, or download a [release](https://github.com/cydrobolt/polr/releases). 
 - To get started with the Polr API, check out the [API guide](http://docs.polr.me/en/latest/developer-guide/api/).


Installation TL;DR: clone or download this repository, set document root to `public/`, create MySQL database, go to `yoursite.com/setup` and follow instructions.

### Demo

To test out the demo, head to [demo.polr.me](http://demo.polr.me) and use the following credentials:

- Username: `demo-admin`
- Password: `demo-admin`

### Upgrading Polr
*Upgrading from 1.x:*

There are breaking changes between 2.x and 1.x; it is not yet possible to automatically upgrade to 2.x.

*Upgrading from 2.x:*
 - Back up your database and files
 - Update by using `git pull` or downloading a release
 - Run `composer install --no-dev -o` to ensure dependencies are up to date
 - Migrate with `php artisan migrate` to ensure database structure is up to date

#### Browser Extensions

* Safari - [Polr.safariextension](https://github.com/cleverdevil/Polr.safariextension)

#### Versioning

Polr uses [Semantic Versioning](http://semver.org/)


#### License


    Copyright (C) 2013-2017 Chaoyi Zha

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
