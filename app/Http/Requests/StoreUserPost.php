<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPost extends FormRequest
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'start' => 'required',
            'description' => 'required',
            'company' => 'required',
            'position' => 'required',
            'avatar' => 'required',
            'sign' => 'required',
            'role' => 'required',
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
