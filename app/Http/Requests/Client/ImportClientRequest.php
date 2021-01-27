<?php

namespace ThaoHR\Http\Requests\Client;

use ThaoHR\Http\Requests\Request;

class ImportClientRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required'
        ];
    }
}
