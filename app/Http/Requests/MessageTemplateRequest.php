<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MessageTemplateRequest extends FormRequest
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
            'content' => 'required|max: 1000',
            'listener_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'テンプレート名を入力してください。',
            'name.max' => 'テンプレート名は150文字以下で入力してください。',
            'content.required' => 'テンプレート本文を入力してください。',
            'content.max' => 'テンプレート本文は1000文字以下で入力してください。',
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
