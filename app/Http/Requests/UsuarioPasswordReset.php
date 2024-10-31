<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioPasswordReset extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'correo' => 'required|email|exists:users,correo',
        ];

        return $rules;
    }
    public function messages()
    {
        return [
            'correo.exists' => 'No se encontró ningún usuario con este correo',
        ];
    }
}
