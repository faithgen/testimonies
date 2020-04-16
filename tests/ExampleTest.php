<?php

namespace Faithgen\Testimonies\Tests;

use Faithgen\Testimonies\TestimoniesServiceProvider;
use Tests\TestCase;

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
