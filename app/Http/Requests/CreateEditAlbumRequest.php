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
            'keyword' => 'required|folder_keyword_pattern|keyword_space_check|max:3',
            'album_name' => 'required|max:3'
        ];
    }
    
    public function messages() {
        return [
            //'keyword.required' => __('customValidation.keyword_required'),
            'album_name.required' => __('customValidation.album_name_required'),
            'keyword.max' => __('customValidation.keyword_has_too_many_characters'),
            'album_name.max' => __('customValidation.album_name_has_too_many_characters'),
        ];
    }
}
