<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListenerRequest extends FormRequest
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
            'last_name' => 'max: 150',
            'first_name' => 'max: 150',
            'last_name_kana' => 'max: 150',
            'first_name_kana' => 'max: 150',
            'radio_name' => 'max: 150',
            'post_code' => 'integer',
            'prefecture' => 'max: 150',
            'city' => 'max: 150',
            'house_number' => 'max: 255',
            'building' => 'max: 255',
            'room_number' => 'max: 255',
            'tel' => 'max: 100',
            'email' => 'required|email|unique:listeners',
            // TODO: パスワードの制限検討
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'post_code.integer' => '郵便番号は数字で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスは正しい形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に使用されています。',
            'password.required' => 'パスワードを入力してください。'
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
