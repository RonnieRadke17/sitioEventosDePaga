<?php

namespace App\Http\Requests\ActivityRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:255',
            'mix'  => 'required|in:0,1',
            'sport_id'  => 'required|exists:sports,id',//faltan los comentarios de la validación
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'El nombre es obligatorio.',
            'name.string'       => 'El nombre debe ser una cadena de texto.',
            'name.min'          => 'El nombre debe tener al menos 5 caracteres.',
            'name.max'          => 'El nombre no puede exceder los 255 caracteres.',

            'mix.required'      => 'El campo mix es obligatorio.',
            'mix.in'            => 'El campo mix debe ser 0 o 1.',

            'sport_id.required' => 'El campo sport_id es obligatorio.',
            'sport_id.exists'   => 'El deporte seleccionado no es válido o no existe en la base de datos.',
        ];
    }

}
