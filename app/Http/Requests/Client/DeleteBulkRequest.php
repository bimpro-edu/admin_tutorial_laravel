<?php

namespace ThaoHR\Http\Requests\Client;

use ThaoHR\Http\Requests\Request;

class DeleteBulkRequest extends Request
{
    public function rules()
    {
        return [
            'client_ids' => 'required'
        ];
    }
}
