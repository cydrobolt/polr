# Configuration
-----------------

## Basic configuration



## Performance optimization

If your Polr installation is heavily used you can tweak some configuration to increase the performance.

A key part of Polr is in the `performRedirect` method. Here is where
the translation of the short url to the long url happens, but also there is some validation and tracking 
that could generate latency during the resolution.

### Cache

By default Polr uses file cache to avoid hitting the database server on every request, if you swap
file based for another driver of the ones supported on Lumen you will reduce the load of your server.
The supported drivers are :
* File (default)
* Memcached
* Redis

For more information on the server requirements and configuration look at:
https://lumen.laravel.com/docs/5.1/cache

### Queues

There are tasks that are better to perform on as a background operation, like tracking data for a
link. By default Polr uses the `sync` driver of Lumen. But it can be easy be swapped for an asynchronous
driver like:

* Sync (default)
* Database
* Amazon SQS
* Beanstalkd
* IronMQ
* Redis

For more information on the server requirements look at:
https://lumen.laravel.com/docs/5.1/queues
