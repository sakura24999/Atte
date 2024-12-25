<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|string|max:191|unique:users,email',
            'password' => 'required|min:8|max:191'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は必須です',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は191文字以内で入力してください',

            'email.required' => 'メールアドレスは必須です',
            'email.email' => '有効なメールアドレス形式で入力してください',
            'email.string' => 'メールアドレスは文字列で入力してください',
            'email.max' => 'メールアドレスは191文字以内で入力してください',
            'email.unique' => 'メールアドレスは既に使用されています',

            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは191文字以内で入力してください'
        ];
    }
}
