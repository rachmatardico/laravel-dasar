<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceContainerTest extends TestCase
{
    public function testDependency()
    {
        $foo = $this->app->make(Foo::class); // new Foo()
        $foo2 = $this->app->make(Foo::class);// new Foo()

        self::assertEquals("Foo", $foo->foo());
        self::assertEquals("Foo", $foo2->foo());
        self::assertNotSame($foo, $foo2);
    }

    public function testBind()
    {
        // $person = $this->app->make(Person::class);
        // self::assertNotNull($person);

        $this->app->bind(Person::class, function($app){
            return new Person("Rachmat", "Ardico");
        });

        $person1 = $this->app->make(Person::class); // closure() // new Person("Rachmat", "Ardico");
        $person2 = $this->app->make(Person::class); // closure() // new Person("Rachmat", "Ardico");

        self::assertEquals("Rachmat", $person1->firstName);
        self::assertEquals("Ardico", $person2->lastName);
        self::assertNotSame($person1, $person2);
    }

    public function testSingleton()
    {
        $this->app->singleton(Person::class, function($app){
            return new Person("Rachmat", "Ardico");
        });

        $person1 = $this->app->make(Person::class); // new Person("Rachmat", "Ardico"); if not exists
        $person2 = $this->app->make(Person::class); // return existing

        self::assertEquals("Rachmat", $person1->firstName);
        self::assertEquals("Ardico", $person2->lastName);
        self::assertSame($person1, $person2);
    }

    public function testInstance()
    {
        $person = new Person("Rachmat", "Ardico");
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class); // $person
        $person2 = $this->app->make(Person::class); // $person

        self::assertEquals("Rachmat", $person1->firstName);
        self::assertEquals("Ardico", $person2->lastName);
        self::assertSame($person1, $person2);
    }

    public function testDepedencyInjection()
    {
        $this->app->singleton(Foo::class, function($app){
            return new Foo();
        });

        $this->app->singleton(Bar::class, function($app){
            $foo = $app->make(Foo::class);
            return new Bar($foo);
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);
        
        self::assertSame($foo, $bar1->foo);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        // $this->app->singleton(HelloService::class, HelloServiceIndonesia::class);

        $this->app->singleton(HelloService::class, function($app){
            return new HelloServiceIndonesia();
        });
        
        $helloService = $this->app->make(HelloService::class);

        self::assertEquals("Halo Rachmat", $helloService->hello("Rachmat"));
    }
}
