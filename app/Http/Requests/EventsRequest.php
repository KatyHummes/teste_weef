<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventsRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'string', 'max:255'],
            'responsible' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'images' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'Este campo é obrigatório',
            'string' => 'Este campo deve ser uma string',
            'max' => 'Este campo deve ter no máximo 255 caracteres',
        ];
    }
}
