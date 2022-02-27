<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorStoreRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;
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
        return ["username"=>"required|min:5|unique:users"];
    }

    public function messages()
    {
        return["username.required"=>"hold on ma boi username is required",
               "username.min"=>"username must be more than 5 charachters",
               "username.unique"=>"username already exists"];
    }
}
