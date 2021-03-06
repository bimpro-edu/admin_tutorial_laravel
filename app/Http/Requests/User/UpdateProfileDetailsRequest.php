<?php

namespace ThaoHR\Http\Requests\User;

use ThaoHR\Http\Requests\Request;
use ThaoHR\User;

class UpdateProfileDetailsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'birthday' => 'nullable|date',
        ];
    }
}
