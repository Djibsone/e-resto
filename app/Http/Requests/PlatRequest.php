<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:4'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'url' => ['required', 'string'],
            // 'url' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'restaurant_id' => ['required', 'integer'],
        ];
    }
}
