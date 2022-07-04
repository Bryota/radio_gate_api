<?php

namespace App\Http\Requests;

use App\Http\Requests\TypeCastedFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestFunctionListenerSubmitRequest extends TypeCastedFormRequest
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
            'point' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'point.required' => 'ポイントを入力してください。',
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
