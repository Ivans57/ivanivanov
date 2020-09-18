<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditKeywordRequest extends FormRequest
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
        //We need to pass an old keyword to validation
        //because we need to compare it with a new keyword to avoid any misunderstanding
        //when do keyword uniqueness check.
        //When we edit existing record we might change something without changing
        //a keyword. If we don't compare new keyword with its previous value, the system
        //might think keyword is not unique as user is trying to assign already existing keyword.
        return [
            'keyword' => 'required|bail|keywords_prohibited_characters|space_check|keyword_uniqueness_check:'.$this->request->get('old_keyword').'|max:50',
            'section' => 'max:50',
            'text' => 'required'
        ];
    }
    
    //We need function messages only if we have overriden regular messages
    //I left the lines below just for example
    /*public function messages() {
        return [
        ];
    }*/
}
