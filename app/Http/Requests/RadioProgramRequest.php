<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RadioProgramRequest extends FormRequest
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
            'radio_station_id' => 'required',
            'name' => 'required|max: 100',
            'email' => 'required|unique:radio_programs'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ラジオ番組名を入力してください。',
            'name.max' => 'ラジオ番組名は100文字以下で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.unique' => 'メールアドレスが既に使われています。',
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
