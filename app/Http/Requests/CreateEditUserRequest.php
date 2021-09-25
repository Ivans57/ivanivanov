<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditUserRequest extends FormRequest
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
        //We need to pass an old username to validation
        //because we need to compare it with a new username to avoid any misunderstanding
        //when do username uniqueness check.
        //When we edit existing record we might change something without changing
        //a username. If we don't compare new username with its previous value, the system
        //might think username is not unique as user is trying to assign already existing username.
        return [
            'name' => 'required|bail|space_check|users_prohibited_characters|'.'username_uniqueness_check:'.
                      $this->request->get('old_username').'|max:30',
            'email' => 'required|bail|email|space_check|'.'email_uniqueness_check:'.$this->request->get('old_email').'|max:50',
            'password' => 'required|confirmed|min:6'
        ];
    }
    
    //We need function messages only if we have overriden regular messages
    //I left the lines below just for example
    /*public function messages() {
        return [
        ];
    }*/
}
