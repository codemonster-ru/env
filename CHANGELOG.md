# Changelog

All notable changes to this package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/ru/1.1.0/),
and this project follows [Semantic Versioning](https://semver.org/lang/ru/).

## [Unreleased]

## [1.1.0] - 2025-09-23

### Added

-   `Env::get($key, $default = null)` method with type casting:
-   `true` / `(true)` → `true`
-   `false` / `(false)` → `false`
-   `null` / `(null)` → `null`
-   `empty` / `(empty)` → `""`
-   Global helper `env($key, $default = null)`.
-   Support for quoted strings (`"..."`, `'...'`).
-   New tests (7 tests, 8 assertions) for all cases.
-   Configuration file `phpunit.xml.dist`.
-   The `CHANGELOG.md` file.
-   The `README.md` file with usage examples.

## [1.0.0] - 2025-09-22

### Added

-   The first stable version of the `codemonster-ru/env` package.
-   The `Env::load($path)` method for loading the `.env` file into the environment.

---

[Unreleased]: https://github.com/codemonster-ru/env-php/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/codemonster-ru/env-php/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/codemonster-ru/env-php/releases/tag/v1.0.0
