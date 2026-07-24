<?php

use Illuminate\Support\Str;

return [
    'route' => '/vite-env.js',

    'global' => '__ENV__',

    'prefix' => 'VITE_',

    'version' => Str::random(8),

    'vars' => collect($_SERVER)->merge($_ENV)
        ->filter(fn ($value, $key) => str_starts_with($key, 'VITE_'))
        ->all(),
];
