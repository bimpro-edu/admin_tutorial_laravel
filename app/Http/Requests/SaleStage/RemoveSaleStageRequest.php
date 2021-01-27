<?php

namespace ThaoHR\Http\Requests\SaleStage;

use ThaoHR\Http\Requests\Request;

class RemoveSaleStageRequest extends Request
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
