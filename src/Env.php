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
            $parsed = self::parseLine($line);

            if ($parsed === null) {
                continue;
            }

            [$name, $value] = $parsed;

            if (
                array_key_exists($name, $_ENV) ||
                array_key_exists($name, $_SERVER) ||
                getenv($name) !== false
            ) {
                continue;
            }

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;

            putenv("$name=$value");
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

    protected static function parseLine(string $line): ?array
    {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            return null;
        }

        if (str_starts_with($line, 'export ')) {
            $line = trim(substr($line, 7));
        }

        $pos = self::findUnquotedEquals($line);

        if ($pos === null) {
            $name = trim($line);

            return $name === '' ? null : [$name, ''];
        }

        $name = trim(substr($line, 0, $pos));

        if ($name === '') {
            return null;
        }

        $value = trim(substr($line, $pos + 1));

        if ($value === '') {
            return [$name, ''];
        }

        $first = $value[0];

        if ($first === '"' || $first === "'") {
            return [$name, self::parseQuotedValue($value, $first)];
        }

        $value = self::stripInlineComment($value);
        $value = self::unescapeUnquotedValue($value);

        return [$name, $value];
    }

    protected static function findUnquotedEquals(string $line): ?int
    {
        $inSingle = false;
        $inDouble = false;
        $escaped = false;
        $length = strlen($line);

        for ($i = 0; $i < $length; $i++) {
            $ch = $line[$i];

            if ($escaped) {
                $escaped = false;

                continue;
            }

            if ($ch === '\\') {
                $escaped = true;

                continue;
            }

            if ($ch === "'" && !$inDouble) {
                $inSingle = !$inSingle;

                continue;
            }

            if ($ch === '"' && !$inSingle) {
                $inDouble = !$inDouble;

                continue;
            }

            if ($ch === '=' && !$inSingle && !$inDouble) {
                return $i;
            }
        }

        return null;
    }

    protected static function parseQuotedValue(string $value, string $quote): string
    {
        $out = '';
        $escaped = false;
        $length = strlen($value);

        for ($i = 1; $i < $length; $i++) {
            $ch = $value[$i];

            if ($escaped) {
                $out .= self::unescapeChar($ch);
                $escaped = false;

                continue;
            }

            if ($ch === '\\') {
                $escaped = true;

                continue;
            }

            if ($ch === $quote) {
                $rest = trim(substr($value, $i + 1));

                if ($rest !== '' && !str_starts_with($rest, '#')) {
                    // Ignore trailing non-comment content.
                }

                return $out;
            }

            $out .= $ch;
        }

        return $quote . $out;
    }

    protected static function unescapeChar(string $ch): string
    {
        return match ($ch) {
            'n' => "\n",
            'r' => "\r",
            't' => "\t",
            '\\' => '\\',
            '"' => '"',
            "'" => "'",
            default => $ch,
        };
    }

    protected static function stripInlineComment(string $value): string
    {
        $escaped = false;
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $ch = $value[$i];

            if ($escaped) {
                $escaped = false;

                continue;
            }

            if ($ch === '\\') {
                $escaped = true;

                continue;
            }

            if ($ch === '#') {
                return rtrim(substr($value, 0, $i));
            }
        }

        return $value;
    }

    protected static function unescapeUnquotedValue(string $value): string
    {
        $out = '';
        $escaped = false;
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $ch = $value[$i];

            if ($escaped) {
                if ($ch === '#' || $ch === '=' || $ch === '\\') {
                    $out .= $ch;
                } else {
                    $out .= '\\' . $ch;
                }

                $escaped = false;

                continue;
            }

            if ($ch === '\\') {
                $escaped = true;

                continue;
            }

            $out .= $ch;
        }

        if ($escaped) {
            $out .= '\\';
        }

        return $out;
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
