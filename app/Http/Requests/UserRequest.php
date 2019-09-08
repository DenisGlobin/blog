<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'alpha_dash|min:2|max:100|unique:users',
            'email' => 'string|email|max:255|unique:users',
            'firstName' => 'string|max:100|nullable',
            'lastName' => 'string|max:100|nullable',
        ];
    }
}
