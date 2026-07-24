<?php

namespace Clickman6\LaravelViteEnv;

class ViteEnv
{
    public static function body(): string
    {
        return sprintf(
            'window.%s = %s;',
            config('vite-env.global', '__ENV__'),
            json_encode((object) config('vite-env.vars', []), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    public static function url(): string
    {
        return route('vite-env.js') . '?v=' . config('vite-env.version');
    }
}
