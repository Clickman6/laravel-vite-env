<?php

namespace Clickman6\LaravelViteEnv\Http\Controllers;

use Clickman6\LaravelViteEnv\ViteEnv;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ViteEnvController extends Controller
{
    public function __invoke(): Response
    {
        return response(ViteEnv::body())
            ->header('Content-Type', 'application/javascript; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=31536000, immutable');
    }
}
