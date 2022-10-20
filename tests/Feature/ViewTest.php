<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    public function testView()
    {
        $this->get('/hello')
            ->assertSeeText("Hello Rachmat");

        $this->get('/hello-again')
            ->assertSeeText("Hello Rachmat");
    }

    public function testNestedView()
    {
        $this->get('/hello-world')
            ->assertSeeText("World Rachmat");
    }

    public function testTemplate()
    {
        $this->view('hello', ['name' => 'Rachmat'])
            ->assertSeeText("Hello Rachmat");

            $this->view('hello.world', ['name' => 'Rachmat'])
            ->assertSeeText("World Rachmat");
    }
}
