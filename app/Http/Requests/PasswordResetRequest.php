<?php

namespace App\Http\Requests;

use App\Http\Requests\TypeCastedFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PasswordResetRequest extends TypeCastedFormRequest
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
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'token' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスは正しい形式で入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.confirmed' => 'パスワードが確認用と異なっています。',
        ];
    }

    protected function failedValidation($validator)
    {
        $response = response()->json([
            'status' => 422,
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
