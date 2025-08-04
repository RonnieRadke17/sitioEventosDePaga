<?php

namespace App\Http\Requests\ActivityEventRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityEventRequest extends FormRequest
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
            'selected_activities'     => ['required', 'array'],
            'selected_activities.*'   => ['integer', 'exists:activities,id'],

            'genders.*'               => ['array'],
            'genders.*.*'             => ['array'],
            'genders.*.*.*'           => ['required', 'integer', 'exists:subs,id'],
        ];
    }



    public function messages(): array
{
    return [
        'selected_activities.required'    => 'Debe seleccionar al menos una actividad.',
        'selected_activities.array'       => 'El formato de las actividades seleccionadas no es válido.',
        'selected_activities.*.exists'    => 'Una o más actividades seleccionadas no existen en la base de datos.',
        'selected_activities.*.integer'   => 'El identificador de la actividad debe ser un número entero.',

        'genders.*.array'                 => 'El formato de género para alguna actividad no es válido.',
        'genders.*.*.array'              => 'El formato de subcategorías para algún género no es válido.',
        'genders.*.*.*.required'         => 'Debe seleccionar al menos una subcategoría.',
        'genders.*.*.*.integer'          => 'El identificador de la subcategoría debe ser un número entero.',
        'genders.*.*.*.exists'           => 'Una o más subcategorías seleccionadas no existen en la base de datos.',
    ];
}

}
