<?php

namespace ThaoHR\Http\Requests\Client;

use ThaoHR\Http\Requests\Request;

class CreateClientRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:clients,code',
            'address' => 'required',
            'phone' => 'required|unique:clients,phone',
        ];
    }
}
