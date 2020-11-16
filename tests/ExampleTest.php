<?php

namespace RexlManu\LaravelTickets\Tests;

use Orchestra\Testbench\TestCase;
use RexlManu\LaravelTickets\LaravelTicketsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelTicketsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
