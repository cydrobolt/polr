<?php

return [

    'database' => [
        '_' => 'Database Configuration',
        'host' => 'Database Host:',
        'port' => 'Database Port:',
        'username' => 'Database Username:',
        'password' => 'Database Password:',
        'name' => 'Database Name:',
        'nametooltip' => 'Name of existing database. You must create the Polr database manually.',
    ],

    'app' => [
        '_' => 'Application Settings',
        'name' => 'Application Name:',
        'protocol' => 'Application Protocol:',
        'url' => 'Application URL (path to Polr; do not include http:// or trailing slash):',
        'advanalytics' => [
            '_' => 'Advanced Analytics:',
            'tooltip' => 'Enable advanced analytics to collect data such as referers, geolocation, and clicks over time. Enabling advanced analytics reduces performance and increases disk space usage.',
            'enable' => 'Enable advanced analytics',
            'disable' => 'Disable advanced analytics',
        ],
        'perm' => [
            '_' => 'Shortening Permissions:',
            'anyone' => 'Anyone can shorten URLs',
            'logged' => 'Only logged in users may shorten URLs',
        ],
        'public' => [
            '_' => 'Public Interface:',
            'enable' => 'Show public interface (default)',
            'disable' => 'Redirect index page to redirect URL',
        ],
        'disabled' => [
            '_' => '404s and Disabled Short Links:',
            'err' => 'Show an error message (default)',
            'redir' => 'Redirect index page to redirect URL',
        ],
        'redirurl' => [
            '_' => 'Redirect URL:',
            'tooltip' => 'Required if you wish to redirect the index page or 404s to a different website. To use Polr, login by directly heading to yoursite.com/login first.',
            'tooltip2' => 'If a redirect is enabled, you will need to go to http://yoursite.com/login before you can access the index page.',
        ],
        'urlendingtype' => [
            '_' => 'Default URL Ending Type:',
            'tooltip' => 'If you choose to use pseudorandom strings, you will not have the option to use a counter-based ending.',
            'base' => 'Use base62 or base32 counter (shorter but more predictable, e.g 5a)',
            'strings' => 'Use pseudorandom strings (longer but less predictable, e.g 6LxZ3j)',
        ],
        'urlendingbase' => [
            '_' => 'URL Ending Base:',
            'tooltip' => 'This will have no effect if you choose to use pseudorandom endings.',
            '32' => '32 -- lowercase letters & numbers (default)',
            '62' => '62 -- lowercase, uppercase letters & numbers',
        ],
    ],

    'admin' => [
        '_' => 'Admin Account Settings',
        'tooltip' => 'These credentials will be used for your admin account in Polr.',
        'username' => 'Admin Username:',
        'email' => 'Admin Email:',
        'password' => 'Admin Password:',
    ],

    'smtp' => [
        '_' => 'SMTP Settings',
        'tooltip' => 'Required only if the email verification or password recovery features are enabled.',
        'server' => 'SMTP Server:',
        'port' => 'SMTP Port:',
        'username' => 'SMTP Username:',
        'password' => 'SMTP Password:',
        'from' => 'SMTP From:',
        'fromname' => 'SMTP From Name:',
    ],

    'api' => [
        '_' => 'API Settings',
        'anonymous' => [
            '_' => 'Anonymous API:',
            'off' => 'Off -- only registered users can use API',
            'on' => 'On -- empty key API requests are allowed',
        ],
        'quota' => [
            '_' => 'Anonymous API Quota:',
            'tooltip' => 'API quota for non-authenticated users per minute per IP.',
        ],
        'autoapi' => [
            '_' => 'Automatic API Assignment:',
            'off' => 'Off -- admins must manually enable API for each user',
            'on' => 'On -- each user receives an API key on signup',
        ],
    ],

    'other' => [
        '_' => 'Other Settings',
        'register' => [
            '_' => 'Registration:',
            'tooltip' => 'Enabling registration allows any user to create an account.',
            'off' => 'Registration disabled',
            'email' => 'Enabled, email verification required',
            'on' => 'Enabled, no email verification required',
        ],
        'restrictemail' => [
            '_' => 'Restrict Registration Email Domains:',
            'tooltip' => 'Restrict registration to certain email domains.',
            'any' => 'Allow any email domain to register',
            'restrict' => 'Restrict email domains allowed to register',
            'emaillist' => 'Permitted Email Domains:',
            'listtooltip' => 'A comma-separated list of emails permitted to register.',
        ],
        'passwordrecover' => [
            '_' => 'Password Recovery:',
            'tooltip' => 'Password recovery allows users to reset their password through email.',
            'tooltip2' => 'Please ensure SMTP is properly set up before enabling password recovery.',
            'enabled' => 'Password recovery enabled',
            'disabled' => 'Password recovery disabled',
        ],
        'recaptcha' => [
            '_' => 'Require reCAPTCHA for Registrations',
            'tooltip' => 'You must provide your reCAPTCHA keys to use this feature.',
            'require' => 'Require reCATPCHA for registration',
            'notrequire' => 'Do not require reCAPTCHA for registration',
            'config' => [
                '_' => 'reCAPTCHA Configuration:',
                'tooltip' => 'You must provide reCAPTCHA keys if you intend to use any reCAPTCHA-dependent features.',
                'site' => 'reCAPTCHA Site Key',
                'secret' => 'reCAPTCHA Secret Key',
                'text' => 'You can obtain reCAPTCHA keys from :link.',
                'link' => 'Google\'s reCAPTCHA website',
            ],
        ],
        'theme' => [
            '_' => 'Theme (:link):',
            'link' => 'screenshots',
        ],
    ],

    'submit' => 'Install',
    'clear' => 'Clear Fields',

    'footer' => [
        'opensource' => [
            '_' => 'Polr is :link1 licensed under the :link2.',
            'link1' => 'open-source software',
            'link2' => 'GPLv2+ License',
        ],
        'version' => 'Polr Version :version released :month :day, :year',
        'copyright' => 'Copyright :year',
        'ampersand' => '&',
        'other' => 'other Polr contributors',
    ],

];