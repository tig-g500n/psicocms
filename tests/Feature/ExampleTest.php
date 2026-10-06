<?php

namespace Tests\Feature;

use App\Models\Psicologa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_uninstalled_application_redirects_to_the_installer(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/instalacion');
    }

    public function test_an_installed_application_can_leave_the_installer(): void
    {
        Psicologa::create([
            'nombre' => 'Karin',
            'apellidos' => 'Prueba',
            'email_privado' => 'karin@example.com',
            'telefono_privado' => '0000000000',
            'password' => 'password-seguro',
        ]);

        $this->get('/instalacion')->assertRedirect('/');
        $this->get('/')->assertOk();
    }
}
