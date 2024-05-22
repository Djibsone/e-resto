<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
            'name_resto' => ['required', 'string'],
            'localisation' => ['required', 'string'],
            'url' => ['required', 'string'],
            'open_hour' => ['required', 'string'],
            'close_hour' => ['required', 'string'],
            'numero_resto' => ['required', 'string', 'min:8'],
            'description' => ['string'],
            'address' => ['required', 'string'],
            // 'user_id' => ['required', 'integer'],
            'user_id' => ['integer'],
        ];
    }
}
