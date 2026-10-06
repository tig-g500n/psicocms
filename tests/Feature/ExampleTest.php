<?php

namespace Tests\Feature;

use App\Models\Psicologa;
use App\Models\ImagenWeb;
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

    public function test_a_custom_web_image_is_served_from_the_database(): void
    {
        Psicologa::create([
            'nombre' => 'Karin',
            'apellidos' => 'Prueba',
            'email_privado' => 'karin@example.com',
            'telefono_privado' => '0000000000',
            'password' => 'password-seguro',
        ]);

        ImagenWeb::create([
            'clave' => 'hero',
            'ruta' => 'hero.png',
            'mime' => 'image/png',
            'contenido_base64' => base64_encode('imagen-de-prueba'),
        ]);

        $this->get('/media/web/hero')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertContent('imagen-de-prueba');
    }
}
