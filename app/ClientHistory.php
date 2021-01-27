<?php

namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientHistory extends Model
{
    protected $table = 'client_histories';
    
    protected $fillable = ['id', 'client_id', 'sale_stage', 'content', 'note'];
    
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s');
    }
}
