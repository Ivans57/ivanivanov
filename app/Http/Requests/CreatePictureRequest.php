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
            'included_in_album_with_id'  => 'item_has_directory',
            'image_select'  => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'keyword' => 'required|bail|prohibited_characters|space_check|picture_keyword_uniqueness_check:'.$this->request->get('old_keyword').'|max:50',
            'picture_caption' => 'required|max:50'
        ];
    }
}
