<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsFormRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['nullable'],
            'subject' => ['nullable'],
            'message' => ['required'],
            'category' => ['required'],
            'order_number' => ['nullable'],
            'captcha' => ['required','captcha']
        ];
    }

    public function messages()
    {
        return [
            'captcha.captcha' => 'Captcha verification failed'
        ];
    }
}
