<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePictureRequest extends FormRequest
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
            'keyword' => 'required|bail|prohibited_characters|space_check|album_keyword_uniqueness_check:'.$this->request->get('old_keyword').'|max:50',
            'album_name' => 'required|max:50'
        ];
    }
}
