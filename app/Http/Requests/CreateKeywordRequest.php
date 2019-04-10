<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//As we use JavaScipt to authorise filled form, we temporary do not need this file.
class CreateKeywordRequest extends FormRequest
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
            'keyword' => 'required',
            'text' => 'required'
        ];
    }
}
