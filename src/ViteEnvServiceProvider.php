<?php

namespace Clickman6\LaravelViteEnv;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViteEnvServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vite-env.php', 'vite-env');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/vite-env.php' => config_path('vite-env.php'),
        ], 'vite-env-config');

        $this->publishes([
            __DIR__ . '/../resources/js/env.js' => resource_path('js/vendor/laravel-vite-env/env.js'),
        ], 'frontend-assets');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Blade::directive('viteEnv', function () {
            return "<?php echo '<script src=\"' . e(\Clickman6\LaravelViteEnv\ViteEnv::url()) . '\"></script>'; ?>";
        });
    }
}
