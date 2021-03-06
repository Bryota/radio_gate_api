<?php

namespace App\Http\Requests;

use App\Http\Requests\TypeCastedFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListenerMyProgramRequest extends TypeCastedFormRequest
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
            'name' => 'required|max: 150',
            'email' => 'required|max: 150',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '番組名を入力してください。',
            'name.max' => '番組名は150文字以下で入力してください。',
            'email.required' => '番組メールアドレスを入力してください。',
            'email.max' => '番組メールアドレスは150文字以下で入力してください。',
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
