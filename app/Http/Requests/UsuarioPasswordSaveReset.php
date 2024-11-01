<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioPasswordSaveReset extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'token' => 'required',
            // 'correo' => 'required|email|exists:users,correo',
            'password' => 'required|string|min:8|confirmed',
        ];


        return $rules;
    }
    public function messages()
    {
        return [
            // 'correo.exists' => 'No se encontró ningún usuario con este correo',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];
    }
}
