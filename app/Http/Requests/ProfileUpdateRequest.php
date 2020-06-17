<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {;
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        ];

        if($request->has('password') && Hash::check($request->input('password'), $user->password)) {
            $rules['new_password'] = 'required|between:6,255';
            $rules['new_password_confirmation'] = 'required|same:new_password|min:6';
        }

        return $rules;
    }

    public function messages()
    {
        $user = Auth::user();

        $messages = [
            'name.required'  => 'Adınızı daxil edin',
            'email.required'  => 'İşlək email daxil edin',
            'email'     => 'Doğru email ünvanını daxil edin',
            'unique:users,email'    => 'Bu emaillə istifadəçi mövcuddur',
        ];

        if($this->request->has('password') && Hash::check($this->request->get('password'), $user->password)){
            $messages['new_password.required'] = 'Yeni şifrəni daxil edin';
            $messages['new_password_confirmation.required'] = 'Yeni şifrənin təkrarını daxil edin';
            $messages['new_password_confirmation.same'] = 'Şifrənin təsdiqi yalnışdır';
            $messages['between'] = 'Şifrə ən az 6 xarakterli olmalıdır';
        }

        return $messages;
    }
}
