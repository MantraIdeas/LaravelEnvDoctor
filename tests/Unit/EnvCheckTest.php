<?php

use Mantraideas\LaravelEnvDoctor\Checks\EnvCheck;

beforeEach(function () {
    $this->keys = ['UNIT_PASS', 'UNIT_MISSING', 'UNIT_EMPTY', 'UNIT_NULL', 'UNIT_A', 'UNIT_B'];

    foreach ($this->keys as $key) {
        unset($_ENV[$key], $_SERVER[$key]);
    }
});

afterEach(function () {
    foreach ($this->keys as $key) {
        unset($_ENV[$key], $_SERVER[$key]);
    }
});

it('returns pass when key exists and has a value', function () {
    $_ENV['UNIT_PASS'] = 'MyApp';

    $results = EnvCheck::run(['UNIT_PASS']);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('pass')
        ->and($results[0]['message'])->toContain('UNIT_PASS');
});

it('returns fail when key is missing', function () {
    $results = EnvCheck::run(['UNIT_MISSING']);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('missing');
});

it('returns fail when key exists but value is empty string', function () {
    $_ENV['UNIT_EMPTY'] = '';

    $results = EnvCheck::run(['UNIT_EMPTY']);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('empty');
});

it('returns fail when key exists but value is null', function () {
    $_ENV['UNIT_NULL'] = null;

    $results = EnvCheck::run(['UNIT_NULL']);

    expect($results)->toHaveCount(1)
        ->and($results[0]['status'])->toBe('fail')
        ->and($results[0]['message'])->toContain('empty');
});

it('handles an empty array of keys', function () {
    expect(EnvCheck::run([]))->toBe([]);
});

it('handles multiple keys with mixed results', function () {
    $_ENV['UNIT_A'] = 'value';

    $results = EnvCheck::run(['UNIT_A', 'UNIT_B']);

    expect($results)->toHaveCount(2)
        ->and($results[0]['status'])->toBe('pass')
        ->and($results[1]['status'])->toBe('fail');
});
