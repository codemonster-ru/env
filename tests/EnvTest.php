<?php

use Codemonster\Env\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    protected function setUp(): void
    {
        foreach (
            [
                'APP_NAME',
                'FEATURE_ENABLED',
                'FEATURE_DISABLED',
                'OPTIONAL_VALUE',
                'EMPTY_VALUE',
                'SSR_URL',
                'QUOTED_SINGLE',
                'QUOTED_DOUBLE',
                'QUOTED_WITH_COMMENT',
                'WITH_SPACES',
                'INLINE_COMMENT',
                'ESCAPED_HASH',
                'ESCAPED_EQUALS',
                'MULTILINE',
                'EXPORTED_VALUE'
            ] as $key
        ) {
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
        $this->assertSame('Hello "Double"', Env::get('QUOTED_DOUBLE'));
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

    public function testEnvHandlesInlineComments(): void
    {
        $this->assertSame('works', Env::get('INLINE_COMMENT'));
        $this->assertSame('Hello # not comment', Env::get('QUOTED_WITH_COMMENT'));
    }

    public function testEnvHandlesSpacesAroundEquals(): void
    {
        $this->assertSame('spaced value', Env::get('WITH_SPACES'));
    }

    public function testEnvHandlesEscapedChars(): void
    {
        $this->assertSame('foo#bar', Env::get('ESCAPED_HASH'));
        $this->assertSame('foo=bar', Env::get('ESCAPED_EQUALS'));
    }

    public function testEnvHandlesEscapedNewlines(): void
    {
        $this->assertSame("line1\nline2\rline3", Env::get('MULTILINE'));
    }

    public function testEnvHandlesExportPrefix(): void
    {
        $this->assertSame('exported', Env::get('EXPORTED_VALUE'));
    }

    public function testEnvDoesNotOverrideGetenv(): void
    {
        unset($_ENV['APP_NAME'], $_SERVER['APP_NAME']);
        putenv('APP_NAME=SystemApp');

        Env::load(__DIR__ . '/.env.example');

        $this->assertSame('SystemApp', Env::get('APP_NAME'));
    }
}
