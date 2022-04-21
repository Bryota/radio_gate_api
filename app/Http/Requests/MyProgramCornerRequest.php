<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MyProgramCornerRequest extends FormRequest
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
            'listener_my_program_id' => 'required',
            'corner_name' => 'required|max: 100',
            'listener_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'corner_name.required' => 'ラジオ番組名を入力してください。',
            'corner_name.max' => 'ラジオ番組名は100文字以下で入力してください。',
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
