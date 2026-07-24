# laravel-vite-env

Serves a public `/config.js` with `VITE_*` environment values, sourced from Laravel's `config()`
(not directly from `.env`). No file generation, works with `php artisan config:cache`, and fits
immutable Docker images.

## Installation

```bash
composer require clickman6/laravel-vite-env
```

The provider is registered automatically via Laravel package discovery.

## Quick start

In `.env`:

```
VITE_APP_NAME=MyApp
VITE_API_URL=https://api.example.com
```

Publish the JS helper:

```bash
php artisan vendor:publish --tag=frontend-assets
```

In your Blade template — `@viteEnv` inserts `<script src="/config.js?v=...">` with the current
config version, **before** the main bundle:

```blade
@viteEnv
<script type="module" src="/resources/js/app.js"></script>
```

In frontend code, read values through `env()`, not directly via `window.__ENV__` — it also falls
back to `import.meta.env.VITE_*` for local development without a backend:

```js
import { env } from './vendor/laravel-vite-env/env.js';

console.log(env('VITE_APP_NAME')); // "MyApp"
```

The whitelist is collected automatically: every environment variable prefixed with `VITE_` is
included, no manual key list needed.

## Configuration

Optionally publish the config:

```bash
php artisan vendor:publish --tag=frontend-config
```

`config/frontend.php`:

- `route` — the endpoint path, defaults to `/config.js`.
- `global` — the global JS variable name, defaults to `__ENV__`.
- `prefix` — the environment variable prefix, defaults to `VITE_`.
- `version` — a random string (`Str::random(8)`), generated when the config file is loaded.

`route`/`global`/`prefix` are not read via `env()` — they're structural settings for the package
itself, not deploy secrets, so it's simpler to edit them directly in the published PHP file.
They're deliberately not tied to `.env`, otherwise a `VITE_...`-style variable for configuring the
package itself would fall under the `prefix` filter and leak into the public whitelist.

**Variable interpolation in `.env`.** If you use `VITE_TMP=${APP_TMP}`, the value is resolved by
Laravel/`phpdotenv` before our config file runs — the whitelist gets the already-resolved value.
This only works if `APP_TMP` is defined earlier in `.env` (dotenv parses top to bottom) or already
set as a real OS/container environment variable.

## How it works with `config:cache` and Docker

The variable whitelist is collected **once**, when the config file loads — the same moment that
happens during `php artisan config:cache`, when the whole application boots and the result gets
baked into `bootstrap/cache/config.php`. After that, the runtime only reads `config()` — `.env`
and `getenv()` are never touched while handling a request. That's why `.env` is only needed at
image build time (when `config:cache` runs); it may not exist at all in an immutable container at
runtime.

The endpoint is fully public (no middleware/auth) — safety comes from the fact that only
`VITE_`-prefixed variables are included, i.e. values explicitly meant for the frontend.

## Caching

`/config.js?v=<version>` is served with `Cache-Control: public, max-age=31536000, immutable` —
the browser never re-requests it as long as the URL doesn't change (same idea as fingerprinted
Vite/webpack assets). `version` is baked into `bootstrap/cache/config.php` together with the
whitelist during `php artisan config:cache` and stays constant for the whole lifetime of a
deployment; on the next deploy (new `.env` → `config:cache`) a new random version is generated —
the URL changes and the browser fetches the new JS.

Without `config:cache` (local development), `config/frontend.php` is recomputed on every request
— `version` changes every time, so browser caching effectively doesn't apply, which is expected
in dev.

Always use `@viteEnv` (or `route('frontend.js') . '?v=' . config('frontend.version')`), never a
bare `/config.js` without `?v=` — without a version in the URL, the browser would cache the config
forever with no way to invalidate it.

## License

MIT, see [LICENSE](LICENSE).
