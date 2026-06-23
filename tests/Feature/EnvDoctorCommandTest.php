<?php

afterEach(function () {
    unset($_ENV['TEST_DOCTOR_KEY'], $_SERVER['TEST_DOCTOR_KEY']);
});

it('exits SUCCESS when env keys pass and directories pass', function () {
    $_ENV['TEST_DOCTOR_KEY'] = 'value';

    config([
        'laravel-env-doctor.required_env_keys' => ['TEST_DOCTOR_KEY'],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')->assertExitCode(0);
});

it('exits FAILURE when an env key is missing', function () {
    config([
        'laravel-env-doctor.required_env_keys' => ['MISSING_DOCTOR_KEY'],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')->assertExitCode(1);
});

it('exits FAILURE when a directory is missing', function () {
    $missing = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'env_doctor_missing_' . uniqid('', true);

    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [
            ['path' => $missing, 'required_permission' => 755],
        ],
    ]);

    $this->artisan('env:doctor')->assertExitCode(1);
});

it('outputs the environment variables check header', function () {
    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')
        ->expectsOutputToContain('ENVIRONMENT VARIABLES CHECK')
        ->assertExitCode(0);
});

it('outputs the directory permissions check header', function () {
    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')
        ->expectsOutputToContain('DIRECTORY PERMISSIONS CHECK')
        ->assertExitCode(0);
});

it('outputs the diagnosis summary header', function () {
    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')
        ->expectsOutputToContain('DIAGNOSIS SUMMARY')
        ->assertExitCode(0);
});

it('outputs a success message when no issues are found', function () {
    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')
        ->expectsOutputToContain('Excellent!')
        ->assertExitCode(0);
});

it('outputs a warning message when issues are found', function () {
    config([
        'laravel-env-doctor.required_env_keys' => ['MISSING_DOCTOR_KEY'],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor')
        ->expectsOutputToContain('Some issues')
        ->assertExitCode(1);
});

it('does not throw errors when the --log flag is used', function () {
    config([
        'laravel-env-doctor.required_env_keys' => [],
        'laravel-env-doctor.directories_to_check' => [],
    ]);

    $this->artisan('env:doctor', ['--log' => true])->assertExitCode(0);
});
