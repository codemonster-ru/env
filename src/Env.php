<?php

namespace Codemonster\Env;

class Env
{
    public static function load(string $path): void
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("Env file not found: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$name, $value] = array_pad(explode('=', $line, 2), 2, null);

            $name = trim($name);
            $value = trim($value ?? '');
            $value = self::stripQuotes($value);

            if (!array_key_exists($name, $_ENV) && !array_key_exists($name, $_SERVER)) {
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                putenv("$name=$value");
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $_ENV)) {
            return self::castValue($_ENV[$key]);
        }

        if (array_key_exists($key, $_SERVER)) {
            return self::castValue($_SERVER[$key]);
        }

        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return self::castValue($value);
    }

    protected static function stripQuotes(string $value): string
    {
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    protected static function castValue(string $value): mixed
    {
        $lower = strtolower($value);

        return match ($lower) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            'empty', '(empty)' => '',
            default => $value,
        };
    }
}
