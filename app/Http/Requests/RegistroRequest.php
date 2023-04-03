<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class RegistroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRules::min(8)->letters()->symbols()->numbers()
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El Email no es valido',
            'email.unique' => 'Ya hay un usuario con ese Correo',
            'password.required' => 'La contraseña es obligatoria',
            'password' => 'La contraseña debe tener al menos 8 caracteres que incluyan numeros y caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden ',
        ];
    }
}
