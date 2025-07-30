<?php

namespace App\Http\Requests\EventMultimediaRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventMultimediaRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',

            'cover' => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'kit' => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            'content' => 'sometimes|array',
            'content.*' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'El evento es obligatorio.',
            'event_id.exists' => 'El evento seleccionado no existe.',

            'cover.image' => 'La portada debe ser una imagen.',
            'cover.mimes' => 'La portada debe ser de tipo JPG, PNG, GIF o SVG.',
            'cover.max' => 'La imagen de portada no debe superar los 2MB.',

            'kit.image' => 'El kit debe ser una imagen.',
            'kit.mimes' => 'El kit debe ser de tipo JPG, PNG, GIF o SVG.',
            'kit.max' => 'La imagen del kit no debe superar los 2MB.',

            'content.array' => 'Las imágenes de contenido deben ser un arreglo.',
            'content.*.image' => 'Cada imagen debe ser un archivo válido de imagen.',
            'content.*.mimes' => 'Las imágenes deben ser JPG, PNG, GIF o SVG.',
            'content.*.max' => 'Cada imagen no debe superar los 2MB.',
        ];
    }
}
