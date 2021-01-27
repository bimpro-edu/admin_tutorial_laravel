<?php

namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;

class SaleStage extends Model
{
    protected $table = 'sale_stages';

    protected $fillable = ['id', 'name', 'code', 'description'];
}
