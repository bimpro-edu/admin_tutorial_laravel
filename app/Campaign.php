<?php

namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = ['name', 'code', 'description'];
}
