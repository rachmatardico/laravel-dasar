<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvironmentTest extends TestCase
{
    public function testGetEnv()
    {
        $youtube = env('YOUTUBE');

        self::assertEquals('Programmer Zaman Now', $youtube);
    }

    public function testDefaultEnv()
    {
        $author = env("AUTHOR", "Matt");

        self::assertEquals("Matt", $author);
    }
}
