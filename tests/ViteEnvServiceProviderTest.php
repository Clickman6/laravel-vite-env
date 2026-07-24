<?php

namespace Tests;

use Clickman6\LaravelViteEnv\ViteEnvServiceProvider;
use Illuminate\Support\ServiceProvider;

class ViteEnvServiceProviderTest extends TestCase
{
    public function test_it_registers_the_publishable_config(): void
    {
        $paths = ServiceProvider::pathsToPublish(ViteEnvServiceProvider::class, 'vite-env-config');

        $this->assertSame([config_path('vite-env.php')], array_values($paths));
    }

    public function test_it_registers_the_publishable_frontend_asset(): void
    {
        $paths = ServiceProvider::pathsToPublish(ViteEnvServiceProvider::class, 'vite-env-js');

        $this->assertSame([resource_path('js/vendor/laravel-vite-env/env.js')], array_values($paths));
    }
}
