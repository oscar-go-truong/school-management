<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest

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
            'email' => 'required|max:255',
            'username'=> 'required|max:255',
            'password'=> 'nullable|min:8|max:32',
            'repassword'=> 'nullable|min:8|max:32|same:password',
            'fullname'=>'required',
            'role'=>'required|integer|between:1,3'
        ];
    }
}
