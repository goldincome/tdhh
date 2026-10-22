<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment' => 'required|string|max:255',
            'billing_address' => 'required|string',
            'guest_name' => 'required|string',
            'guest_email' => 'required|string|email',
            'guest_phone' => 'required|string',
            'payment' => 'required|string',
 
        ];
    }
}
