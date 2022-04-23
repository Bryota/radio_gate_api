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
            if ($this->input('program_corner_id')) {
                return [
                    'content' => 'required',
                    'radio_program_id' => 'required',
                    'program_corner_id' => 'required'
                ];
            } else {
                return [
                    'content' => 'required',
                    'radio_program_id' => 'required',
                    'subject' => 'required'
                ];
            }
        } else {
            if ($this->input('my_program_corner_id')) {
                return [
                    'content' => 'required',
                    'listener_my_program_id' => 'required',
                    'my_program_corner_id' => 'required'
                ];
            } else {
                return [
                    'content' => 'required',
                    'listener_my_program_id' => 'required',
                    'subject' => 'required'
                ];
            }
        }
    }

    public function messages()
    {
        return [
            'content.required' => '本文を入力してください。',
            'radio_program_id.required' => '番組を選択してください。',
            'listener_my_program_id.required' => '番組を選択してください。',
            'program_corner_id.required' => 'コーナーを選択するか件名を入力してください。',
            'my_program_corner_id.required' => 'コーナーを選択するか件名を入力してください。',
            'subject.required' => 'コーナーを選択するか件名を入力してください。'
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
