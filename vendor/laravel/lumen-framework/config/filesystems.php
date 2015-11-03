<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "s3", "rackspace"
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        's3' => [
            'driver'   => 's3',
            'key'      => env('S3_KEY'),
            'secret'   => env('S3_SECRET'),
            'region'   => env('S3_REGION'),
            'bucket'   => env('S3_BUCKET'),
            'base_url' => env('S3_URL'),
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => env('RACKSPACE_USERNAME'),
            'key'       => env('RACKSPACE_KEY'),
            'container' => env('RACKSPACE_CONTAINER'),
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => env('RACKSPACE_REGION'),
            'url_type'  => 'publicURL',
        ],

    ],

];
