<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Required Environment Variables
    |--------------------------------------------------------------------------
    |
    | These are the essential environment variables your Laravel application
    | needs to function properly. The checker will verify they exist and
    | aren't empty. Default values are only for reference in the check.
    |
    */
    'required_env_keys' => [
        'APP_NAME',
        'APP_ENV',
        'APP_KEY',
        'APP_DEBUG',
        'APP_URL',

        'APP_LOCALE',
        'APP_FALLBACK_LOCALE',
        'APP_FAKER_LOCALE',

        'APP_MAINTENANCE_DRIVER',

        'PHP_CLI_SERVER_WORKERS',

        'BCRYPT_ROUNDS',

        'LOG_CHANNEL',
        'LOG_STACK',
//        'LOG_DEPRECATIONS_CHANNEL',
        'LOG_LEVEL',

        'DB_CONNECTION',
//        'DB_HOST',
//        'DB_PORT',
//        'DB_DATABASE',
//        'DB_USERNAME',
//        'DB_PASSWORD',

        'SESSION_DRIVER',
        'SESSION_LIFETIME',
        'SESSION_ENCRYPT',
        'SESSION_PATH',
//        'SESSION_DOMAIN',

        'BROADCAST_CONNECTION',
        'FILESYSTEM_DISK',
        'QUEUE_CONNECTION',

        'CACHE_STORE',

        'MEMCACHED_HOST',

        'REDIS_CLIENT',
        'REDIS_HOST',
//        'REDIS_PASSWORD',
        'REDIS_PORT',

        'MAIL_MAILER',
//        'MAIL_SCHEME',
        'MAIL_HOST',
        'MAIL_PORT',
//        'MAIL_USERNAME',
//        'MAIL_PASSWORD',
        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME',

//        'AWS_ACCESS_KEY_ID',
//        'AWS_SECRET_ACCESS_KEY',
        'AWS_DEFAULT_REGION',
//        'AWS_BUCKET',
        'AWS_USE_PATH_STYLE_ENDPOINT',

        'VITE_APP_NAME',

    ],

    /*
    |--------------------------------------------------------------------------
    | Directory Permissions Check
    |--------------------------------------------------------------------------
    |
    | These directories must be writable for proper application functioning.
    | Recommended permissions:
    | - 0775 for storage and cache directories (owner and group can write)
    | - 0777 only if absolutely necessary (less secure)
    |
    */
    'directories_to_check' => [
        [
            'path' => storage_path(),
            'required_permission' => 755,
        ],
        [
            'path' => storage_path('logs'),
            'required_permission' => 755,
        ],
        [
            'path' => storage_path('framework'),
            'required_permission' => 755,
        ],
        [
            'path' => storage_path('framework/sessions'),
            'required_permission' => 755,
        ],
        [
            'path' => storage_path('framework/views'),
            'required_permission' => 755,
        ],
        [
            'path' => base_path('bootstrap/cache'),
            'required_permission' => 755,
        ],
    ],
];
