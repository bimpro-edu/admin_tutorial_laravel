<?php
namespace ThaoHR\Services\Export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ThaoHR\Client;
use ThaoHR\User;
use ThaoHR\SaleStage;

class ExportSaleStageFollowingStaff implements IExport
{   
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
        if (!empty($allClients) && $allClients->count() > 1) {
            $results = [];
            $headers = [];
            $headers[] = 'NHÂN VIÊN';
            $saleStages = SaleStage::all();
            foreach ($saleStages as $st) {
                $headers[] = $st->name;
            }
            $headers[] = 'TỔNG SỐ';
            $footers['NHÂN VIÊN'] = 'TỔNG SỐ';
            foreach ($saleStages as $st) {
                $footers[$st->name] = 0;
            }
            $footers['TỔNG SỐ'] = 0;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
           
            foreach ($allClients as $client) {
                if (empty($client->assignUser->id)) continue;
                if (!empty($results[$client->assignUser->id])) {
                    $results[$client->assignUser->id];
                } else {
                    $results[$client->assignUser->id]['NHÂN VIÊN'] = $client->assignUser->fullName();
                    foreach ($saleStages as $st) {
                        $results[$client->assignUser->id][$st->name] = 0;
                    }
                    $results[$client->assignUser->id]['TỔNG SỐ'] = 0;
                }
                if (!empty($client->saleStage->name)) {
                    $results[$client->assignUser->id][$client->saleStage->name] += 1;
                    $footers[$client->saleStage->name] += 1;
                }
                
                
                $results[$client->assignUser->id]['TỔNG SỐ'] += 1;
                $footers['TỔNG SỐ'] += 1;
            }
            $results[] = $footers;
            
           
            for($i = 0; $i < count($headers); $i++ ) {
                $col = $this->getNameFromNumber($i);
                $sheet->setCellValue($col.'1', $headers[$i]);
            }
            $j = 2;
            foreach ($results as $value) {
                $index = 0;
                foreach ($headers as $h) {
                    $col = $this->getNameFromNumber($index);
                    $sheet->setCellValue($col.$j, $value[$h]);
                    $index ++;
                }
                
                $j ++;
                
            }
          
            
            $writer = new Xlsx($spreadsheet);
            $filePath =  public_path().'/export/export_sale_stage_following_staff-'.date('Y-m-d_h-i').'.xlsx';
            $writer->save($filePath);
            chmod($filePath, 0777); 
            return 'export/export_sale_stage_following_staff-'.date('Y-m-d_h-i').'.xlsx';
        }
    }
    
    public function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
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

