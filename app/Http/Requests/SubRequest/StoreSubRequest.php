<?php

namespace App\Http\Requests\SubRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:30|unique:subs,name',
            'min' => 'required|integer|min:6|max:100',
            'max' => 'required|integer|min:6|max:100|gt:min',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no debe superar los :max caracteres.',
            'name.unique' => 'Ya existe una categoría con ese nombre.',

            'min.required' => 'El valor mínimo de edad es obligatorio.',
            'min.integer' => 'El valor mínimo debe ser un número entero.',
            'min.min' => 'La edad mínima no puede ser menor a :min.',
            'min.max' => 'La edad mínima no puede ser mayor a :max.',

            'max.required' => 'El valor máximo de edad es obligatorio.',
            'max.integer' => 'El valor máximo debe ser un número entero.',
            'max.min' => 'La edad máxima no puede ser menor a :min.',
            'max.max' => 'La edad máxima no puede ser mayor a :max.',
            'max.gt' => 'La edad máxima debe ser mayor que la mínima.',
        ];
    }

}
