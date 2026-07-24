<?php

namespace Tests;

use Clickman6\LaravelViteEnv\ViteEnv;
use Illuminate\Support\Facades\Blade;

class ViteEnvTest extends TestCase
{
    public function test_url_appends_the_config_version_as_a_query_param(): void
    {
        config()->set('vite-env.version', 'abc123');

        $this->assertSame(route('vite-env.js') . '?v=abc123', ViteEnv::url());
    }

    public function test_directive_renders_an_escaped_script_tag(): void
    {
        config()->set('vite-env.version', 'abc123');

        $html = trim(Blade::render('@viteEnv'));

        $this->assertSame('<script src="' . e(ViteEnv::url()) . '"></script>', $html);
    }
}
