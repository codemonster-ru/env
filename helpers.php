<?php

if (!function_exists('env')) {
    function env(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
}
