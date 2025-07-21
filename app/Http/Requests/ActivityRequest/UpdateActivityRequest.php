<?php

namespace App\Http\Requests\ActivityRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
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
            'name' => 'sometimes|string|min:3|max:100|unique:activities,name',
            'sport_id' => 'sometimes|exists:sports,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'El nombre debe ser texto.',
            'name.min' => 'Debe tener al menos 5 caracteres.',
            'name.unique' => 'El nombre ya está en uso. Debe ser único.',


            'sport_id.exists' => 'El deporte seleccionado no es válido.',
        ];
    }

}
