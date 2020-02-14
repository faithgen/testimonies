<?php

namespace Faithgen\Testimonies\Tests;

use Orchestra\Testbench\TestCase;
use Faithgen\Testimonies\TestimoniesServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [TestimoniesServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
