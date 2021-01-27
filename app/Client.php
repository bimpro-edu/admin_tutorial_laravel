<?php

namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INAVTIVE = 0;
    const DELETED_YES = 1;
    const DELETED_NO = 0;
    
    const NOT_CALLED = 13;
    const NOT_ANSWER = 9;
    
    protected $table = 'clients';

    protected $fillable = ['id', 'name', 'code', 'national_id', 'phone', 'address', 'disbursement_amount', 'assign_by_user_id',
        'assign_user_id', 'campaign_id', 'import_date', 'sale_stage_id', 'money_limit', 'description', 'status', 'deleted'];
    
    public function assignUser()
    {
        return $this->belongsTo(User::class, 'assign_user_id');
    }
    
    public function assignByUser()
    {
        return $this->belongsTo(User::class, 'assign_by_user_id');
    }
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    
    public function saleStage()
    {
        return $this->belongsTo(SaleStage::class, 'sale_stage_id');
    }
    
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y h:i:s');
    }
  
}
