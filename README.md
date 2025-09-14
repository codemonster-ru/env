# codemonster-ru/env

> Minimalistic `.env` rider for PHP projects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codemonster/env.svg?style=flat-square)](https://packagist.org/packages/codemonster-ru/env)
[![Tests](https://github.com/codemonster-ru/env/actions/workflows/tests.yml/badge.svg)](https://github.com/codemonster-ru/env/actions)

## Installation

Via [Composer](https://getcomposer.org):

```bash
composer require codemonster/env
```

## Usage

Create a `.env` file in the root of the project:

```env
APP_NAME=MyApp
APP_ENV=local
APP_DEBUG=true
PORT=8000
```

Usage in PHP:

```php
<?php

use Codemonster\Env;

require __DIR__ . '/vendor/autoload.php';

$env = new Env(__DIR__ . '/.env');

echo $env->get('APP_NAME'); // MyApp
echo $env->get('PORT', 3000); // 8000
echo $env->get('UNKNOWN_KEY', 'default'); // default
```

## Helper

You can use the global `env()` function:

```php
echo env('APP_NAME');
```

## Tests

You can run tests with the command:

```bash
composer test
```

## License

This package is distributed under the MIT license. For more information, see [LICENSE](LICENSE).
