<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeCastedFormRequest extends FormRequest
{
    /**
     * string型に変換
     * 
     * @param string $input_value リクエストの値
     * @return string 型変換された値
     */
    public function string(string $input_value): string
    {
        return strval($this->input($input_value));
    }

    /**
     * integer型に変換
     * 
     * @param string $input_value リクエストの値
     * @return int 型変換された値
     */
    public function integer(string $input_value): int
    {
        return intval($this->input($input_value));
    }
}
