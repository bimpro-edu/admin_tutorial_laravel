<?php

namespace ThaoHR\Http\Requests\Client;

use ThaoHR\Http\Requests\Request;

class RemoveClientRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    }

    public function rules()
    {
        return [];
    }
}
