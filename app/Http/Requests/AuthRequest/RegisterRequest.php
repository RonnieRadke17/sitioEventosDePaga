<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u', // solo letras, espacios y guiones
            ],
            'paternal' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
            ],
            'maternal' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
            ],
            'birthdate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . now()->subYears(10)->format('Y-m-d'),  // al menos 10 años
                'after_or_equal:' . now()->subYears(80)->format('Y-m-d'),   // máximo 80 años
            ],
            'gender' => ['required', 'in:M,F'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                'regex:/[a-z]/',      // al menos una minúscula
                'regex:/[A-Z]/',      // al menos una mayúscula
                'regex:/[0-9]/',      // al menos un número
                'regex:/[@$!%*#?&]/', // al menos un símbolo especial
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios y guiones.',

            'paternal.required' => 'El apellido paterno es obligatorio.',
            'paternal.string' => 'El apellido paterno debe ser una cadena de texto.',
            'paternal.min' => 'El apellido paterno debe tener al menos 2 caracteres.',
            'paternal.max' => 'El apellido paterno no puede tener más de 50 caracteres.',
            'paternal.regex' => 'El apellido paterno solo puede contener letras, espacios y guiones.',

            'maternal.required' => 'El apellido materno es obligatorio.',
            'maternal.string' => 'El apellido materno debe ser una cadena de texto.',
            'maternal.min' => 'El apellido materno debe tener al menos 2 caracteres.',
            'maternal.max' => 'El apellido materno no puede tener más de 50 caracteres.',
            'maternal.regex' => 'El apellido materno solo puede contener letras, espacios y guiones.',

            'birthdate.required' => 'La fecha de nacimiento es obligatoria.',
            'birthdate.date' => 'La fecha de nacimiento no tiene un formato válido.',
            'birthdate.date_format' => 'La fecha debe tener el formato YYYY-MM-DD.',
            'birthdate.before_or_equal' => 'Debes tener al menos 10 años.',
            'birthdate.after_or_equal' => 'La edad no puede ser mayor a 80 años.',

            'gender.required' => 'El sexo es obligatorio.',
            'gender.in' => 'El valor del sexo debe ser M (masculino) o F (femenino).',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 64 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo especial (@$!%*#?&).',
        ];
    }


}
