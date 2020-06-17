<?php

namespace App\Http\Requests;

use http\Client\Curl\User;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;

class StuffStoreRequest extends FormRequest
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
        $rules = [
            'name'       => 'required|string|min:2',
            'email'      => "required|email|unique:users,email, {$this->request->get('user_id')}",
            'role_id'    => 'required|numeric',
            'group_id'   => 'required|numeric',
        ];

        if ($this->request->has('password')){
            $rules['password'] = 'required|min:6';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'email.required'    => 'Email vacib sahədir',
            'name.required'     => 'Ad vacib sahədir',
            'email.unique'      => 'Bu email artıq qeydiyyatdan keçib',
            'password.min'      => 'Şifrə ən az :min xarakter olmalıdır',
            'name.min'          => 'Ad ən az :min xarakter olmalıdır',
        ];

        if ($this->request->has('password')){
            $messages['password.required'] = 'Şifrə vacib sahədir';
        }

        return $messages;
    }
}
