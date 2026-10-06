<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class PerfilPublicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'eslogan' => ['nullable', 'string', 'max:255'],
            'num_colegiado' => ['nullable', 'string', 'max:100'],
            'telefono_citas' => ['nullable', 'string', 'max:30'],
            'email_citas' => ['nullable', 'email', 'max:255'],
            'sobre_mi' => ['nullable', 'string'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'lugar_consulta' => ['nullable', 'string', 'max:255'],
            'mapa_lat' => ['nullable', 'string', 'max:50'],
            'mapa_lng' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email_citas' => 'email para citas',
            'telefono_citas' => 'teléfono para citas',
        ];
    }
}
