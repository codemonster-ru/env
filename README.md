# codemonster-ru/env

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codemonster-ru/env.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/env)
[![Total Downloads](https://img.shields.io/packagist/dt/codemonster-ru/env.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/env)
[![License](https://img.shields.io/packagist/l/codemonster-ru/env.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/env)
[![Tests](https://github.com/codemonster-ru/env-php/actions/workflows/tests.yml/badge.svg)](https://github.com/codemonster-ru/env-php/actions/workflows/tests.yml)

A simple and lightweight `.env` loader for PHP projects.

## Installation

Via Composer:

```bash
composer require codemonster-ru/env
```

## Usage

Create a .env file in the root of your project:

```dotenv
APP_NAME=MyApp
FEATURE_ENABLED=true
FEATURE_DISABLED=false
OPTIONAL_VALUE=null
EMPTY_VALUE=empty
SSR_URL="http://localhost:3000"
```

Load .env in your app:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Codemonster\Env;

Env::load(__DIR__ . '/.env');

echo env('APP_NAME'); // "MyApp"
var_dump(env('FEATURE_ENABLED')); // true (bool)
var_dump(env('FEATURE_DISABLED')); // false (bool)
var_dump(env('OPTIONAL_VALUE')); // null
var_dump(env('EMPTY_VALUE')); // ""
echo env('SSR_URL'); // http://localhost:3000
echo env('NOT_DEFINED', 'default'); // "default"
```

## Features

-   Loading `.env` files into `$_ENV`, `$_SERVER`, and via `putenv()`.
-   Boolean value support:
-   `true`, `(true)` → `true`
-   `false`, `(false)` → `false`
-   Support for `null` and `empty`:
-   `null`, `(null)` → `null`
-   `empty`, `(empty)` → `""`
-   Support for quoted strings `"..."` and `'...'`.
-   Global function `env($key, $default = null)`.

## Tests

You can run tests with the command:

```bash
composer test
```

## Author

[**Kirill Kolesnikov**](https://github.com/KolesnikovKirill)

## License

[MIT](https://github.com/codemonster-ru/env-php/blob/main/LICENSE)
