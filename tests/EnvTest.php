<?php

use Codemonster\Env\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    protected function setUp(): void
    {
        foreach ([
            'APP_NAME',
            'FEATURE_ENABLED',
            'FEATURE_DISABLED',
            'OPTIONAL_VALUE',
            'EMPTY_VALUE',
            'SSR_URL',
            'QUOTED_SINGLE',
            'QUOTED_DOUBLE'
        ] as $key) {
            unset($_ENV[$key], $_SERVER[$key]);
            putenv($key);
        }

        Env::load(__DIR__ . '/.env.example');
    }

    public function testEnvLoadsString(): void
    {
        $this->assertSame('MyApp', env('APP_NAME'));
    }

    public function testEnvCastsTrueFalse(): void
    {
        $this->assertTrue(env('FEATURE_ENABLED'));
        $this->assertFalse(env('FEATURE_DISABLED'));
    }

    public function testEnvCastsNull(): void
    {
        $this->assertNull(env('OPTIONAL_VALUE'));
    }

    public function testEnvCastsEmpty(): void
    {
        $this->assertSame('', env('EMPTY_VALUE'));
    }

    public function testEnvRemovesQuotes(): void
    {
        $this->assertSame('http://localhost:3000', env('SSR_URL'));
    }

    public function testEnvHandlesSingleAndDoubleQuotes(): void
    {
        $this->assertSame('Hello Single', env('QUOTED_SINGLE'));
        $this->assertSame('Hello Double', env('QUOTED_DOUBLE'));
    }

    public function testEnvReturnsDefaultIfNotSet(): void
    {
        $this->assertSame('default', env('NOT_DEFINED', 'default'));
    }

    public function testEnvDoesNotOverrideExisting(): void
    {
        $_ENV['APP_NAME'] = 'ManualApp';

        Env::load(__DIR__ . '/.env.example');

        $this->assertSame('ManualApp', env('APP_NAME'));
    }
}
