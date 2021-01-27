<?php

namespace ThaoHR\Repositories\Client;

use ThaoHR\Client;
use ThaoHR\Events\Client\Created;
use ThaoHR\Events\Client\Deleted;
use ThaoHR\Events\Client\Updated;
use ThaoHR\ClientHistory;
use ThaoHR\SaleStage;
use ThaoHR\User;
use Illuminate\Support\Facades\Auth;

class EloquentClient implements ClientRepository
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Client::all();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, array $params)
    {
        $query = Client::query();

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
        $result = $query->orderBy('id', 'desc')
        ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        if ($saleStageId) {
            $result->appends(['sale_stage_id' => $saleStageId]);
        }
        
        if ($assignUserId) {
            $result->appends(['assign_user_id' => $assignUserId]);
        }
        
        if ($campaingnId) {
            $result->appends(['campaign_id' => $campaingnId]);
        }
        
        if ($rangeTime) {
            $result->appends(['range_time' => $rangeTime]);
        } else {
            if (!empty($params['from_date'])) {
                $result->appends(['from_date' => $params['from_date']]);
            }
            
            if (!empty($params['to_date'])) {
                $result->appends(['to_date' => $params['to_date']]);
            }
        }
        
        if ($perPage) {
            $result->appends(['limit' => $perPage]);
        }

        return $result;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::findByPhone()
     */
    public function findByPhone($phone)
    {
        return Client::where('phone', $phone)->first();
    }
    
    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $user = Auth::user();
        if ($user->hasRole('Staff')) {
            return Client::where('assign_user_id', $user->id)->count();
        } elseif ($user->hasRole('Leader')) {
            $staffIds = User::where('leader_id', $user->id)->pluck('id');
            $staffIds[] = $user->id;
            return Client::whereIn('assign_user_id', $staffIds)->count();
        } elseif ($user->hasRole('Admin') || $user->hasRole('Leaderplus')) {
            return Client::count();
        }
        
        return 0;
        return Client::count();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countClientBy()
     */
    public function countClientBy($userId)
    {
        return Client::where('assign_user_id', $userId)->count();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countCalled()
     */
    public function countCalled()
    {
        $user = Auth::user();
        if ($user->hasRole('Staff')) {
            return Client::where('sale_stage_id', '<>', Client::NOT_CALLED)->where('assign_user_id', $user->id)->count();
        } elseif ($user->hasRole('Leader')) {
            $staffIds = User::where('leader_id', $user->id)->pluck('id');
            $staffIds[] = $user->id;
            return Client::where('sale_stage_id', '<>', Client::NOT_CALLED)->whereIn('assign_user_id', $staffIds)->count();
        } elseif ($user->hasRole('Admin') || $user->hasRole('Leaderplus')) {
            return Client::where('sale_stage_id', '<>', Client::NOT_CALLED)->count();
        }
        
        return 0;
        
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countClientCalled()
     */
    public function countClientCalled($userId)
    {
        return Client::where('sale_stage_id', '<>', Client::NOT_CALLED)->where('assign_user_id', $userId)->count();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countNotCalled()
     */
    public function countNotCalled()
    {
        $user = Auth::user();
        if ($user->hasRole('Staff')) {
            return Client::where('assign_user_id', $user->id)->where(function($query){
                $query->whereNull('sale_stage_id')->orWhere('sale_stage_id', Client::NOT_CALLED);
            })->count();
        } elseif ($user->hasRole('Leader')) {
            $staffIds = User::where('leader_id', $user->id)->pluck('id');
            $staffIds[] = $user->id;
            return Client::whereIn('assign_user_id', $staffIds)->where(function($query){
                $query->whereNull('sale_stage_id')->orWhere('sale_stage_id', Client::NOT_CALLED);
            })->count();
        } elseif ($user->hasRole('Admin') || $user->hasRole('Leaderplus')) {
            return Client::whereNull('sale_stage_id')->orWhere('sale_stage_id', Client::NOT_CALLED)->count();
        }
        
        return 0;
        
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countClientNotCalled()
     */
    public function countClientNotCalled($userId)
    {
        return Client::where('assign_user_id', $userId)->where(function($query){
            $query->whereNull('sale_stage_id')->orWhere('sale_stage_id', Client::NOT_CALLED);
        })->count();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countNotAnswer()
     */
    public function countNotAnswer()
    {
        $user = Auth::user();
        if ($user->hasRole('Staff')) {
            return Client::where('assign_user_id', $user->id)->where('sale_stage_id', Client::NOT_ANSWER)->count();
        } elseif ($user->hasRole('Leader')) {
            $staffIds = User::where('leader_id', $user->id)->pluck('id');
            $staffIds[] = $user->id;
            return Client::whereIn('assign_user_id', $staffIds)->where('sale_stage_id', Client::NOT_ANSWER)->count();
        } elseif ($user->hasRole('Admin') || $user->hasRole('Leaderplus')) {
            return Client::where('sale_stage_id', Client::NOT_ANSWER)->count();
        }
        
        return 0;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::countClientNotAnswer()
     */
    public function countClientNotAnswer($userId)
    {
        return Client::where('assign_user_id', $userId)->where('sale_stage_id', Client::NOT_ANSWER)->count();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Client::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $client = Client::create($data);

        event(new Created($client));
        $saleStage = SaleStage::find($client->sale_stage_id);
        ClientHistory::create([
            'client_id' => $client->id,
            'sale_stage' => $saleStage->name??'',
            'content' => json_encode($client),
            'note' => $client->description
        ]);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $client = $this->find($id);

        $client->update($data);
        
        $saleStage = SaleStage::find($client->sale_stage_id);

        event(new Updated($client));
        ClientHistory::create([
            'client_id' => $client->id,
            'sale_stage' => $saleStage->name??'',
            'content' => json_encode($client),
            'note' => $client->description
        ]);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $client = $this->find($id);

        event(new Deleted($client));

        return $client->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function lists($column = 'name', $key = 'id')
    {
        return Client::pluck($column, $key);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \ThaoHR\Repositories\Client\ClientRepository::clientHistories()
     */
    public function clientHistories($clientId)
    {
        return ClientHistory::where('client_id', $clientId)->get();
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
