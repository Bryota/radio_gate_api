<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeCastedFormRequest extends FormRequest
{
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

    /**
     * boolean型に変換
     * 
     * @param string $input_value リクエストの値
     * @return bool 型変換された値
     */
    public function bool(string $input_value): bool
    {
        return boolval($this->input($input_value));
    }
}
