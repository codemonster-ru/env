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
        $this->assertSame('MyApp', Env::get('APP_NAME'));
    }

    public function testEnvCastsTrueFalse(): void
    {
        $this->assertTrue(Env::get('FEATURE_ENABLED'));
        $this->assertFalse(Env::get('FEATURE_DISABLED'));
    }

    public function testEnvCastsNull(): void
    {
        $this->assertNull(Env::get('OPTIONAL_VALUE'));
    }

    public function testEnvCastsEmpty(): void
    {
        $this->assertSame('', Env::get('EMPTY_VALUE'));
    }

    public function testEnvRemovesQuotes(): void
    {
        $this->assertSame('http://localhost:3000', Env::get('SSR_URL'));
    }

    public function testEnvHandlesSingleAndDoubleQuotes(): void
    {
        $this->assertSame('Hello Single', Env::get('QUOTED_SINGLE'));
        $this->assertSame('Hello Double', Env::get('QUOTED_DOUBLE'));
    }

    public function testEnvReturnsDefaultIfNotSet(): void
    {
        $this->assertSame('default', Env::get('NOT_DEFINED', 'default'));
    }

    public function testEnvDoesNotOverrideExisting(): void
    {
        $_ENV['APP_NAME'] = 'ManualApp';

        Env::load(__DIR__ . '/.env.example');

        $this->assertSame('ManualApp', Env::get('APP_NAME'));
    }
}
