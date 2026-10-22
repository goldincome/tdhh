<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class CreateWalletFundingRequest extends FormRequest
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
            'depositor_name' => 'required|string|max:255',
            'bank_account_id' => 'required|numeric',
            'amount_paid' => 'required|numeric',
            'date_paid' => 'required',
            'teller_image' => 'required',
        ];
    }
}
