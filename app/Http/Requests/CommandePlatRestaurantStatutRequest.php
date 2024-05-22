<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandePlatRestaurantStatutRequest extends FormRequest
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
            'quantity' => ['required', 'integer'],
            'price_unit' => ['required', 'integer'],
            'price_total' => ['required', 'integer'],
            'commande_id' => ['required', 'integer'],
            'plat_id' => ['required', 'integer'],
            'restaurant_id' => ['required', 'integer'],
            'statutCommande_id' => ['required', 'integer'],
        ];
    }
}
