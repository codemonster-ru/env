<?php

use Codemonster\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testEnvLoadsVariables()
    {
        Env::load(__DIR__ . '/.env.example');

        $this->assertEquals('MyApp', env('APP_NAME'));
    }
}
