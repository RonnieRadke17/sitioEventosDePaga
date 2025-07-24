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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60|unique:events,name',
            'description' => 'required|string|max:200',
            'event_date' => 'required|date|after:today',
            'registration_deadline' => 'required|date|after:today|before:event_date',
            'capacity' => 'nullable|numeric|min:5|max:900000',
            'price' => 'nullable|numeric|min:10|max:10000', 
        ];

        /* mensajes de error */
    }
}
