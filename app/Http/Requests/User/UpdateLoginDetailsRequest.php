<?php

namespace ThaoHR\Http\Requests\User;

use ThaoHR\Http\Requests\Request;
use ThaoHR\User;

class UpdateLoginDetailsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->getUserForUpdate();

        return [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8|confirmed'
        ];
    }

    /**
     * @return \Illuminate\Routing\Route|object|string
     */
    protected function getUserForUpdate()
    {
        return $this->route('user');
    }
}
