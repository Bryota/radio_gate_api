<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListenerMessageRequest extends FormRequest
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
        if ($this->input('radio_program_id')) {
            return [
                'listener_id' => 'required',
                'content' => 'required',
                'radio_program_id' => 'required'
            ];
        } else {
            return [
                'listener_id' => 'required',
                'content' => 'required',
                'listener_my_program_id' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'content.required' => '本文を入力してください。',
            'radio_program_id.required' => '番組を入力してください。',
            'listener_my_program_id.required' => '番組を入力してください。',
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
