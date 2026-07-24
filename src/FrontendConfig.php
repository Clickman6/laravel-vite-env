<?php

namespace Clickman6\LaravelViteEnv;

class FrontendConfig
{
    public static function body(): string
    {
        return sprintf(
            'window.%s = %s;',
            config('frontend.global', '__ENV__'),
            json_encode(config('frontend.vars', []), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    public static function url(): string
    {
        return route('frontend.js') . '?v=' . config('frontend.version');
    }
}
