<?php

namespace Mantraideas\LaravelEnvDoctor\Tests;

use Mantraideas\LaravelEnvDoctor\LaravelEnvDoctorServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaravelEnvDoctorServiceProvider::class];
    }
}
