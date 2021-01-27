<?php

namespace ThaoHR\Http\Requests\Campaign;

use ThaoHR\Http\Requests\Request;

class RemoveCampaignRequest extends Request
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
