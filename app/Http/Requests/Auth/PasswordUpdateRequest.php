<?php

namespace ThaoHR\Http\Requests\Auth;

use ThaoHR\Http\Requests\Request;

class PasswordUpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_old' => 'required',
            'password' => 'required|confirmed|min:8'
        ];
    }

    /**
     * Get the password update fields.
     *
     * @return array
     */
    public function getPasswordUpdateFields()
    {
        return $this->only('password_old', 'password', 'password_confirmation');
    }
}
