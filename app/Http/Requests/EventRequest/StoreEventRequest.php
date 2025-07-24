<?php

namespace App\Http\Requests\EventRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:70|unique:events,name',
            'description' => 'required|string|min:10|max:200',
            'event_date' => 'required|date|after:today',
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'capacity' => 'nullable|numeric|min:5|max:900000',
            'price' => 'nullable|numeric|min:10|max:10000', 
        ];

    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del evento es obligatorio.',
            'name.string' => 'El nombre del evento debe ser un texto.',
            'name.min' => 'El nombre del evento debe tener al menos 5 caracteres.',
            'name.max' => 'El nombre del evento no debe exceder los 70 caracteres.',
            'name.unique' => 'Ya existe un evento con ese nombre.',

            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser un texto.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'description.max' => 'La descripción no debe exceder los 200 caracteres.',

            'event_date.required' => 'La fecha del evento es obligatoria.',
            'event_date.date' => 'La fecha del evento debe ser una fecha válida.',
            'event_date.after' => 'La fecha del evento debe ser posterior a hoy.',

            'registration_deadline.required' => 'La fecha límite de inscripción es obligatoria.',
            'registration_deadline.date' => 'La fecha límite de inscripción debe ser una fecha válida.',
            'registration_deadline.after' => 'La fecha límite de inscripción debe ser posterior a hoy.',
            'registration_deadline.before' => 'La fecha límite de inscripción debe ser anterior a la fecha del evento.',

            'capacity.numeric' => 'La capacidad debe ser un número.',
            'capacity.min' => 'La capacidad mínima es de 5 personas.',
            'capacity.max' => 'La capacidad máxima es de 900000 personas.',

            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio mínimo es de 10.',
            'price.max' => 'El precio máximo es de 10000.',
        ];
    }

}
