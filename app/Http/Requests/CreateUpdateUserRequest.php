<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CreateUpdateUserRequest extends FormRequest
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
        $method = Request::method();

        $rules = array(
            'fullname' => 'required',
            'role' => 'required|integer|between:1,3',
            'email' => 'required|unique:users,email|max:255',
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:8|max:32',
            'repassword' => 'required|min:8|max:32:same:password'
        );

        if ($method === "PUT" || $method === "PATCH") {
            $rules['email'] = 'required|max:255|unique:users,email,' . $this->id;
            $rules['username'] = 'required|max:255|unique:users,username,' . $this->id;
            $rules['password'] = 'nullable|min:8|max:32';
            $rules['repassword'] = 'nullable|min:8|max:32|same:password';
        }

        return $rules;
    }
}
