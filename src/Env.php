<?php

namespace Codemonster;

class Env
{
    protected string $path;

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("Env file not found: $path");
        }

        $this->path = $path;
    }

    public function load(): void
    {
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$name, $value] = array_pad(explode('=', $line, 2), 2, null);

            $name = trim($name);
            $value = trim($value ?? '');

            if (!array_key_exists($name, $_ENV) && !array_key_exists($name, $_SERVER)) {
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;

                putenv("$name=$value");
            }
        }
    }
}
