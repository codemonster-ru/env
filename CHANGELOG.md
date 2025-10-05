# Changelog

All notable changes to this package will be documented in this file.

## [Unreleased]

## [2.0.1] - 2025-10-05

### Fixed

-   Removed global `env()` helper from the package to keep framework-level responsibility in `codemonster-ru/annabel`.
-   The package now exposes only the static API through `Codemonster\Env\Env`.

### Docs

-   Updated `README.md` to reflect the removal of the global helper.
    Examples now use `Env::get()` instead of `env()`.

### Tests

-   Updated all tests to use `Env::get()` instead of the removed global `env()` helper.

### Internal

-   Verified autoload configuration in `composer.json` (removed helper file registration).
-   No API-breaking changes; patch-level release only.

## [2.0.0] - 2025-09-28

### Changed

-   Raised minimum PHP version to >= 8.2. No public API changes.

## [1.1.1] - 2025-09-24

### Changed

-   Namespace changed from `Codemonster` to `Codemonster\Env`.
-   Added support for tests with single and double quotes (`'...'` and `"..."`) in `.env`.

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
