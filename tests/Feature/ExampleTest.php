<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_an_uninstalled_application_redirects_to_the_installer(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/instalacion');
    }
}
