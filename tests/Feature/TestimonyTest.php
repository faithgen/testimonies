<?php

namespace Faithgen\Testimonies\Feature;

use Tests\TestCase;

class TestimonyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * A basic feature test example.
     *
     * @return void
     */
    public function example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function is_composer_setup_correct()
    {
        $composerData = json_decode(file_get_contents(__DIR__.'/../../composer.json'));

        $this->assertFalse($composerData->name == 'faithgen/testimoniesd');
        $this->assertTrue($composerData->name == 'faithgen/testimonies');

        $this->assertArrayHasKey('homepage', (array) $composerData);

        $this->assertEquals($composerData->homepage, 'https://github.com/faithgen/testimonies');

        $this->assertArrayNotHasKey('damnit', (array) $composerData);

        $providers = $composerData->extra->laravel->providers;

        $this->assertCount(2, $providers);
    }
}
