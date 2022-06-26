<?php

namespace App\Http\Requests;

use App\Http\Requests\TypeCastedFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InqueryRequest extends TypeCastedFormRequest
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
            'email' => 'required|email|max:150',
            'type' => 'required|max:100',
            'content' => 'required|max:1500',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスは正しい形式で入力してください。',
            'email.max' => 'メールアドレスは150文字以内で入力してください。',
            'type.required' => '問い合わせ種別を入力してください。',
            'type.max' => '問い合わせ種別は150文字以内で入力してください。',
            'content.required' => '詳細を入力してください。',
            'content.max' => '詳細は1500文字以内で入力してください。'
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
