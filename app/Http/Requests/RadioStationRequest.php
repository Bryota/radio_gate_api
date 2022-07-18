<?php

namespace App\Http\Requests;

use App\Http\Requests\TypeCastedFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RadioStationRequest extends TypeCastedFormRequest
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
            'name' => 'required|max: 100',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ラジオ局名を入力してください。',
            'name.max' => 'ラジオ局名は100文字以下で入力してください。',
            'type.required' => 'ラジオ局タイプを入力してください。',
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
