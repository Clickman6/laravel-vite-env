<?php

namespace Tests;

class ViteEnvControllerTest extends TestCase
{
    public function test_it_serves_only_whitelisted_variables_with_cache_headers(): void
    {
        $_SERVER['VITE_APP_NAME'] = 'MyApp';
        $_ENV['VITE_API_URL'] = 'https://api.example.com';
        $_SERVER['DB_PASSWORD'] = 'secret';

        $this->refreshApplication();

        $response = $this->get(route('vite-env.js'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/javascript; charset=UTF-8');

        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('max-age=31536000', $cacheControl);
        $this->assertStringContainsString('immutable', $cacheControl);

        $response->assertSeeText('VITE_APP_NAME');
        $response->assertSeeText('VITE_API_URL');
        $response->assertDontSeeText('DB_PASSWORD');
        $response->assertDontSeeText('secret');

        unset($_SERVER['VITE_APP_NAME'], $_ENV['VITE_API_URL'], $_SERVER['DB_PASSWORD']);
    }

    public function test_it_serves_an_empty_object_when_no_vite_variables_are_set(): void
    {
        $response = $this->get(route('vite-env.js'));

        $response->assertOk();
        $response->assertSee('window.__ENV__ = {};', false);
    }
}
