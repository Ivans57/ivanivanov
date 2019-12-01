<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditAlbumRequest extends FormRequest
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
    public function rules() {
        return [
            'keyword' => 'required|bail|folder_keyword_pattern|keyword_space_check|max:3',
            'album_name' => 'required|max:3'
        ];
    }
    
    //We need function messages only if we have overriden regular messages
    //I left the lines below just for example
    /*public function messages() {
        return [
        ];
    }*/
}
