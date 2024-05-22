<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandeRequest extends FormRequest
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
            'ref_cmde' => ['string'],
            'date_cmde' => ['required', 'date'],
            'price_total' => ['required', 'integer'],
            'address_livraison' => ['required', 'string'],
            'user_id' => ['required', 'integer'],
        ];
    }
}
