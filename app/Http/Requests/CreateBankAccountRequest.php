<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBankAccountRequest extends FormRequest
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
            'country_id' => ['required', 'exists:countries,id'],
            'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'currency_code' => ['required', 'exists:currencies,code'],
            // 'bank_logo' => ['nullable'],
            'routing_number' => ['nullable' ],
            'swift_code' => ['nullable'],
            'status' => ['required', 'in:0,1'],
        ];
    }
}
