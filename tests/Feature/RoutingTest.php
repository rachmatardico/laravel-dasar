<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function testGet()
    {
        $this->get('/pzn')
            ->assertStatus(200)
            ->assertSeeText("Hello Programmer Zaman Now");
    }

    public function testRedirect()
    {
        $this->get('/youtube')
            ->assertRedirect('/pzn');
    }

    public function testFallback()
    {
        $this->get('/tidakada')
            ->assertSeeText("404 by Programmer Zaman Now");

        $this->get('/tidakadalagi')
            ->assertSeeText("404 by Programmer Zaman Now");
            
        $this->get('/ups')
            ->assertSeeText("404 by Programmer Zaman Now");
    }

    public function testRouteParameter()
    {
        $this->get('/products/1')
            ->assertSeeText("Product : 1");

        $this->get('/products/2')
            ->assertSeeText("Product : 2");

        $this->get('/products/1/items/xxx')
            ->assertSeeText("Product : 1, Item : xxx");
        
        $this->get('/products/2/items/yyy')
            ->assertSeeText("Product : 2, Item : yyy");
    }

    public function testRouteParameterRegex()
    {
        $this->get("/categories/100")
            ->assertSeeText("Category : 100");
        
        $this->get("/categories/rachmat")
            ->assertSeeText("404 by Programmer Zaman Now");
    }

    public function testRouteParameterOptional()
    {
        $this->get("/users/rachmat")
            ->assertSeeText("User : rachmat");
        
        $this->get("/users/")
            ->assertSeeText("User : 404");
    }

    public function testRouteConflict()
    {
        $this->get('/conflict/budi')
            ->assertSeeText("Conflict : budi");
        
        $this->get('/conflict/rachmat')
            ->assertSeeText("Conflict : Rachmat Ardico");
    }

    public function testNamedRoute()
    {
        $this->get('/produk/12345')
            ->assertSeeText('Link : http://localhost/products/12345');
        
        $this->get('/produk-redirect/12345')
            ->assertRedirect('/products/12345');
    }
}
