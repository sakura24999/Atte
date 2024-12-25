<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        Log::debug('Rules method called');
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        Log::debug('Message method called');
        return [
            'email.required' => 'メールアドレスは必須です',
            'email.email' => '有効なメールアドレス形式で入力してください',
            'password.required' => 'パスワードは必須です',
        ];
    }

    public function attributes()
    {
        Log::debug('Attributes method called');
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
