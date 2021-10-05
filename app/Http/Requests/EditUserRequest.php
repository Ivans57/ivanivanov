<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //The value below should be true. If it is false, the rules below do not work.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //We need to pass an old username to validation
        //because we need to compare it with a new username to avoid any misunderstanding
        //when do username uniqueness check.
        //When we edit existing record we might change something without changing
        //a username. If we don't compare new username with its previous value, the system
        //might think username is not unique as user is trying to assign already existing username.
        //The same as above applies to a emails.
        return [
            'name' => 'required|bail|space_check|users_prohibited_characters|'.'username_uniqueness_check:'.
                      $this->request->get('old_username').'|max:30',
            'email' => 'required|bail|email|space_check|'.'email_uniqueness_check:'.$this->request->get('old_email').'|max:50',
            //The field password should be nullable, otherwise if user wants to leave it blanc, 
            //there will be an error message about min number of characters.
            'password' => 'nullable|min:6|confirmed'
        ];
    }
}
