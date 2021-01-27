<?php

namespace ThaoHR\Http\Requests\SaleStage;

use ThaoHR\Http\Requests\Request;

class UpdateSaleStageRequest extends Request
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
