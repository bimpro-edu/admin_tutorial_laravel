<?php
namespace ThaoHR\Services\Export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ThaoHR\Client;
use ThaoHR\User;

class ExportClient implements IExport
{   
    protected $fields = [
        'name',
        'code',
        'national_id',
        'phone',
        'address',
        'disbursement_amount',
        'assignUser->fullname',
        'campaign->name',
        'import_date',
        'saleStage->name',
        'money_limit',
        'description',
        'updated_at'
    ];
    
    protected $headers = ['Họ tên','Mã khách hàng', 'CMND/Hộ Chiếu', 
        'Số điện thoại', 'Địa chỉ', 'Số tiền giải ngân', 'Nhân viên quản lý', 
        'Chiến dịch', 'Ngày nhập', 'Trạng thái sale', 'Hạn mức', 'Mô tả', 'Ngày cập nhật'];
    
    protected $exelColumns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
    
    
    public function export($params)
    {
        $query = Client::with('assignUser')->with('saleStage')->with('campaign')->where('status', Client::STATUS_ACTIVE);
        $search = !empty($params['search'])? trim($params['search']) : null;
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('national_id', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        $saleStageId = !empty($params['sale_stage_id'])? $params['sale_stage_id'] : null;
        if (!empty($saleStageId)) {
            $query->where('sale_stage_id', $saleStageId);
        }
        
        $assignUserId = !empty($params['assign_user_id'])? $params['assign_user_id'] : null;
        if (!empty($assignUserId)) {
            $query->where('assign_user_id', $assignUserId);
        }
        
        $campaingnId = !empty($params['campaign_id'])? $params['campaign_id'] : null;
        if (!empty($campaingnId)) {
            $query->where('campaign_id', $campaingnId);
        }
        
        $rangeTime = !empty($params['range_time'])? $params['range_time'] : null;
        if ($rangeTime) {
            $start = null;
            $today = date('Y-m-d');
            if ($rangeTime == '1month') {
                $start = strtotime(date("Y-m-d", strtotime($today)) . "-1 months");
            } elseif ($rangeTime == '2months') {
                $start = strtotime(date("Y-m-d", strtotime($today)) . "-2 months");
            } elseif ($rangeTime == '3months') {
                $start = strtotime(date("Y-m-d", strtotime($today)) . "-3 months");
            } elseif ($rangeTime == '6months') {
                $start = strtotime(date("Y-m-d", strtotime($today)) . "-6 months");
            } elseif ($rangeTime == '1year') {
                $start = strtotime(date("Y-m-d", strtotime($today)) . "-1 years");
            }
            $query->where('import_date', '>=', $start)->where('import_date', '<=', $today);
        } else {
            if (!empty($params['from_date'])) {
                $query->where('import_date', '>=', $params['from_date']);
            }
            
            if (!empty($params['to_date'])) {
                $query->where('import_date', '<=', $params['to_date']);
            }
        }
       
        $user = auth()->user();
        if (!$user->hasRole('Admin')) {
            $childIds = $this->getChildren($user->id);
            $childIds[] = $user->id;
            $query->whereIn('assign_user_id', $childIds);
        }
        $allClients = $query->get();
        if (!empty($allClients)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $i = 0;
            foreach ($this->exelColumns as $col) {
                $sheet->setCellValue($col.'1', $this->headers[$i]);
                $i++;
            }
            $j = 2;
            foreach ($allClients as $client) {
                $k = 0;
                foreach ($this->exelColumns as $col) {
                    if ($this->fields[$k] == 'assignUser->fullname') {
                        $sheet->setCellValue($col.$j, !empty($client->assignUser)?$client->assignUser->fullName() : '');
                    }
                    else if ($this->fields[$k] == 'campaign->name') {
                        $sheet->setCellValue($col.$j, !empty($client->campaign->name)?$client->campaign->name : '');
                    } else if ($this->fields[$k] == 'saleStage->name') {
                        $sheet->setCellValue($col.$j, !empty($client->saleStage->name)? $client->saleStage->name: '' );
                    } else if ($this->fields[$k] == 'phone') {
                        if ($user->role->name == 'Admin') {
                            $sheet->setCellValue($col.$j, $client->{$this->fields[$k]});
                        } else {
                            $sheet->setCellValue($col.$j, 'xxxxxxxxx');
                        }
                    }
                    else {
                        $sheet->setCellValue($col.$j, $client->{$this->fields[$k]});
                    }
                    
                    $k ++;
                }
                $j ++;
            }
          
            
            $writer = new Xlsx($spreadsheet);
            $filePath =  public_path().'/export/export_clients-'.date('Y-m-d_h-i').'.xlsx';
            $writer->save($filePath);
            chmod($filePath, 0777); 
            return 'export/export_clients-'.date('Y-m-d_h-i').'.xlsx';
        }
    }
    
    /**
     * Get child ids of parent
     * @param int $parentId
     * @param array $childIds
     * @return array
     */
    public function getChildren($parentId) {
        $user = User::where('id', $parentId)->first();
        return $user->getAllChildren()->pluck('id')->toArray();
    }
}

