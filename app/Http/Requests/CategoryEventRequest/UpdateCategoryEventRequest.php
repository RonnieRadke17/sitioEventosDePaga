<?php

namespace App\Http\Requests\CategoryEventRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryEventRequest extends FormRequest
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
        'selectedCategories' => 'sometimes|array',
        'selectedCategories.*' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'selectedCategories.array' => 'El formato de las categorias seleccionadas no es válido.',
            'selectedCategories.*.exists' => 'Uno o más categorias seleccionadas no existen en la base de datos.',
        ];
    }
}
