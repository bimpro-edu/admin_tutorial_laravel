<?php

namespace ThaoHR\Http\Requests\Campaign;

use ThaoHR\Http\Requests\Request;

class UpdateCampaignRequest extends Request
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
            'code' => 'required'
        ];
    }
}
