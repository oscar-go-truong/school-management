<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email|max:255',
            'username'=> 'required|unique:users,username|max:255',
            'password'=> 'required|min:8|max:32',
            'repassword'=> 'required|min:8|max:32',
            'fullname'=>'required',
            'role'=>'required|integer|between:1,3'
        ];
    }
}
