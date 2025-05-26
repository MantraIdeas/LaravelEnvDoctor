![Laravel Env Doctor](https://banners.beyondco.de/Laravel%20Env%20Doctor.png?theme=light&packageManager=composer+require&packageName=mantraideas%2Flaravel-env-doctor&pattern=architect&style=style_1&description=Diagnose+your+Laravel+environment+configuration&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Laravel Environment Doctor 🩺

[![Latest Stable Version](http://poser.pugx.org/mantraideas/laravel-env-doctor/v)](https://packagist.org/packages/mantraideas/laravel-env-doctor)
[![Total Downloads](http://poser.pugx.org/mantraideas/laravel-env-doctor/downloads)](https://packagist.org/packages/mantraideas/laravel-env-doctor)
[![License](http://poser.pugx.org/mantraideas/laravel-env-doctor/license)](https://packagist.org/packages/mantraideas/laravel-env-doctor)

A diagnostic tool that checks your Laravel application's environment configuration and directory permissions to prevent common deployment issues.

## Features

- ✅ Checks for required `.env` variables
- 🔒 Verifies directory permissions
- 📊 Provides a summary report
- 📝 Optional logging of results
- 🎨 Beautiful console output

## Installation

Install via Composer:

```bash
composer require mantraideas/laravel-env-doctor
```

## Publish the configuration file:
```php
php artisan vendor:publish --provider="mantraideas\LaravelEnvDoctor\LaravelEnvDoctorServiceProvider"
```

## Usage
Run the environment diagnosis:
```bash
php artisan env:doctor
```

Enable logging to a file:
```bash
php artisan env:doctor --log
```

## Configuration
You can customize the configuration by publishing the config file:

```php
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

        // Add More Variables as needed

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

        // Add more directories as needed
    ],
];

```
## Command Output Example
When you run the command, you will see output similar to this:

```
┌──────────────────────────────────────────────────────────────┐
│ 🩺  Laravel Environment Doctor - System Diagnosis            │
└──────────────────────────────────────────────────────────────┘

Checking your application environment configuration and permissions...

📦 ENVIRONMENT VARIABLES CHECK
Verifying required .env variables are set
+--------+-------------------------+
| Status | Message                 |
+--------+-------------------------+
| ✓ PASS | ✅ APP_NAME is set      |
| ✓ PASS | ✅ APP_ENV is set       |
| ✓ PASS | ✅ APP_KEY is set       |
| ✓ PASS | ✅ DB_CONNECTION is set |
| ✓ PASS | ✅ DB_HOST is set       |
| ✓ PASS | ✅ DB_DATABASE is set   |
| ✓ PASS | ✅ DB_USERNAME is set   |
+--------+-------------------------+

📁 DIRECTORY PERMISSIONS CHECK
Verifying directory permissions and writability
+--------+-----------------------------------------------------------------------------------------------------------------------------+
| Status | Message                                                                                                                     |
+--------+-----------------------------------------------------------------------------------------------------------------------------+
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage is writable (Permissions: 755)                    |
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/logs is writable (Permissions: 755)               |
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework is writable (Permissions: 755)          |
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework/sessions is writable (Permissions: 755) |
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework/views is writable (Permissions: 755)    |
| ✓ PASS | ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/bootstrap/cache is writable (Permissions: 755)            |
+--------+-----------------------------------------------------------------------------------------------------------------------------+


┌───────────────────────────────────┐
│ 🩺  DIAGNOSIS SUMMARY             │
├───────────────────────────────────┤
│ Total checks performed: 13        │
│ Issues found: 0                   │
│ Success rate: 100%                │
└───────────────────────────────────┘

🎉 Excellent! Your environment is perfectly configured!
```

## Log Example
You can also log the results to a file by using the `--log` option and it will be logged to specified log file in the config file. The log file will contain detailed information about the checks performed, including any issues found and their resolutions.

```bash
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ---- ENVIRONMENT VARIABLES CHECK ----
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ APP_NAME is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ APP_ENV is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ APP_KEY is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ DB_CONNECTION is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ DB_HOST is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ DB_DATABASE is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ DB_USERNAME is set
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ---- DIRECTORY PERMISSIONS CHECK ----
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/logs is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework/sessions is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/storage/framework/views is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ✓ PASS: ✅ /Users/dipeshkhanal/projects/Mantra Ideas/OSS/laravelEnvDoctor/bootstrap/cache is writable (Permissions: 755)
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] ----- DIAGNOSIS SUMMARY -----
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] Total checks performed: 13
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] Issues found: 0
[2025-05-26 06:35:44] local.INFO: [EnvDoctor] Success rate: 100%
```

## License

[MIT](https://choosealicense.com/licenses/mit/)

## Author

- [@Dipesh79](https://www.github.com/Dipesh79)

## Support

For support, email [dipeshkhanal79[at]gmail[dot]com](mailto:dipeshkanal79@gmail.com).
