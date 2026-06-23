<?php

use Mantraideas\LaravelEnvDoctor\Checks\DirectoryPermissionCheck;

beforeEach(function () {
    $this->tempDirs = [];
});

afterEach(function () {
    foreach ($this->tempDirs as $dir) {
        if (is_dir($dir)) {
            @chmod($dir, 0777);
            @rmdir($dir);
        }
    }
});

it('returns fail when path is null', function () {
    $results = DirectoryPermissionCheck::run([['path' => null]]);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('not found');
});

it('returns fail when directory does not exist', function () {
    $missing = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'env_doctor_missing_' . uniqid('', true);

    $results = DirectoryPermissionCheck::run([['path' => $missing, 'required_permission' => 755]]);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('not found');
});

it('returns pass when dir exists, is writable and has correct perms', function () {
    $dir = makeTempDir();
    $this->tempDirs[] = $dir;
    chmod($dir, 0755);

    $results = DirectoryPermissionCheck::run([['path' => $dir, 'required_permission' => 755]]);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('pass');
})->skip(PHP_OS_FAMILY === 'Windows', 'chmod is not supported on Windows');

it('returns fail when permissions do not match but dir is writable', function () {
    $dir = makeTempDir();
    $this->tempDirs[] = $dir;
    chmod($dir, 0777);

    $results = DirectoryPermissionCheck::run([['path' => $dir, 'required_permission' => 755]]);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('incorrect permissions');
})->skip(PHP_OS_FAMILY === 'Windows', 'chmod is not supported on Windows');

it('uses default permission 775 when none is specified', function () {
    $dir = makeTempDir();
    $this->tempDirs[] = $dir;
    chmod($dir, 0755);

    $results = DirectoryPermissionCheck::run([['path' => $dir]]);

    expect($results)->toHaveCount(1)
        ->and($results[0]['message'])->toContain('Required: 775');
})->skip(PHP_OS_FAMILY === 'Windows', 'chmod is not supported on Windows');

it('handles multiple directories with mixed results', function () {
    $valid = makeTempDir();
    $this->tempDirs[] = $valid;
    chmod($valid, 0777);

    $missing = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'env_doctor_missing_' . uniqid('', true);

    $results = DirectoryPermissionCheck::run([
        ['path' => $valid, 'required_permission' => 777],
        ['path' => $missing, 'required_permission' => 777],
    ]);

    expect($results)->toHaveCount(2)
        ->and($results[0]['status'])->toBe('pass')
        ->and($results[1]['status'])->toBe('fail');
})->skip(PHP_OS_FAMILY === 'Windows', 'chmod is not supported on Windows');
