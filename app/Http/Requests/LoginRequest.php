<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required'
        ];
    }

    public function messages() {
        return [
            'email.required' => 'Un correo electronico es necesario',
            'email.email' => 'El correo electronico debe ser valido',
            'password.required' => 'La clave es requerida',
            'name.required' => 'The name is required'
        ];
    }
}
