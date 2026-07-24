<?php

namespace Tests;

use Clickman6\LaravelViteEnv\ViteEnvServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ViteEnvServiceProvider::class];
    }
}
