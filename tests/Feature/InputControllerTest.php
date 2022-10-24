<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InputControllerTest extends TestCase
{
    public function testInput()
    {
        $this->get("/input/hello?name=Rachmat")
            ->assertSeeText("Hello Rachmat");
        
        $this->post("/input/hello", [
            "name"  => "Rachmat"
        ])->assertSeeText("Hello Rachmat");
    }

    public function testInputNested()
    {
        $this->post("/input/hello", [
            "name"  => [
                "first" => "Rachmat",
                "last"  => "Ardico"
            ]
        ])->assertSeeText("Hello Rachmat");
    }

    public function testInputAll()
    {
        $this->post("/input/hello/input", [
            "name"  => [
                "first" => "Rachmat",
                "last"  => "Ardico"
            ]
        ])->assertSeeText("name")->assertSeeText("first")
            ->assertSeeText("last")
            ->assertSeeText("Rachmat")
            ->assertSeeText("Ardico");
    }

    public function testInputArray()
    {
        $this->post("/input/hello/array", [
            "products"  => [
                [
                    "name"  =>  "Apple Mac Book Pro",
                    "price" =>  30000000
                ],
                [
                    "name"  =>  "Samsung Galaxy S10",
                    "price" =>  15000000
                ]
            ]
        ])->assertSeeText("Apple Mac Book Pro")
            ->assertSeeText("Samsung Galaxy S10");
    }
    
    public function testInputType()
    {
        $this->post('/input/type', [
            "name"          => "Budi",
            "married"       => true,
            "birth_date"    => '1990-10-10'
        ])->assertSeeText("Budi")->assertSeeText("true")
        ->assertSeeText("1990-10-10");
    }

    public function testFilterOnly()
    {
        $this->post('/input/filter/only', [
            "name"  => [
                "first"     => "Rachmat",
                "middle"    => "Ardico",
                "last"      => "Perdana"
            ]
        ])->assertSeeText("Rachmat")->assertSeeText("Perdana")
            ->assertDontSeeText("Ardico");
    }

    public function testFilterExcept()
    {
        $this->post("/input/filter/except", [
            "username"      => "matt",
            "admin"         => "true",
            "password"      => "rahasia"
        ])->assertSeeText("matt")->assertSeeText("rahasia")
            ->assertDontSeeText("true");
    }

    public function testFilterMerge()
    {
        $this->post("/input/filter/merge", [
            "username"      => "matt",
            "admin"         => "true",
            "password"      => "rahasia"
        ])->assertSeeText("matt")->assertSeeText("rahasia")
            ->assertSeeText("admin")->assertSeeText("false");
    }
}
