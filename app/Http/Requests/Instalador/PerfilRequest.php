<?php

namespace App\Http\Requests\Instalador;

use Illuminate\Foundation\Http\FormRequest;

class PerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eslogan' => ['nullable', 'string', 'max:255'],
            'num_colegiado' => ['nullable', 'string', 'max:100'],
            'telefono_citas' => ['nullable', 'string', 'max:30'],
            'email_citas' => ['nullable', 'email', 'max:255'],
            'sobre_mi' => ['nullable', 'string'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'lugar_consulta' => ['nullable', 'string', 'max:255'],
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
