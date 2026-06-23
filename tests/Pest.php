<?php

uses(Mantraideas\LaravelEnvDoctor\Tests\TestCase::class)->in('Feature');

function makeTempDir(): string
{
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'env_doctor_' . uniqid('', true);
    mkdir($dir, 0777, true);
    return $dir;
}
