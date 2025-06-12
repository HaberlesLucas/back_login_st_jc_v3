<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    //Determinar si el usuario está autorizado a realizar esta solicitud
    public function authorize(): bool
    {
        return true;
    }

    //Obtener las reglas de validación que se aplican a la solicitud
    public function rules(): array
    {
        if ($this->isMethod('PUT') || $this->isMethod('patch')) {
            $userId = $this->route('dni');

            $rules['dni'] = [
                'integer',
                Rule::unique('users', 'dni')->ignore($userId, 'dni')
            ];

            $rules['correo'] = [
                'sometimes',
                'string',
                'email',
                Rule::unique('users', 'correo')->ignore($userId, 'dni'),
            ];

            $rules['password'] = 'nullable|string';
            $rules['apellido_nombre'] = 'sometimes|string';
            return $rules;
        }
        //dd('aaaaaaa');
        $rules = [
            'dni'               => 'required|integer|digits:8|unique:users,dni',
            'apellido_nombre'   => 'required|string',
            'password'          => 'required|string',
            'c_password'        => 'required|same:password',
            'correo'            => 'required|string|email|unique:users,correo',      //nuevo para correo
            'c_correo'          => 'required|same:correo', //nuevo para correo
            'estado'            => 'boolean',
            'roles'             => 'required|array',
        ];


        return $rules;
    }
}
