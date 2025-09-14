<?php

use Codemonster\Env;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testEnvLoadsVariables()
    {
        $env = new Env(__DIR__ . '/.env.example');
        $env->load();

        $this->assertEquals('MyApp', env('APP_NAME'));
    }
}
