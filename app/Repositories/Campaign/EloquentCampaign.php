<?php

namespace ThaoHR\Repositories\Campaign;

use ThaoHR\Campaign;
use ThaoHR\Events\Campaign\Created;
use ThaoHR\Events\Campaign\Updated;
use ThaoHR\Events\Campaign\Deleted;

class EloquentCampaign implements CampaignRepository
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Campaign::all();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null)
    {
        $query = Campaign::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Campaign::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByName($name)
    {
        return Campaign::where('name', $name)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findByCode($code)
    {
        return Campaign::where('code', $code)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $campaign = Campaign::create($data);

        event(new Created($campaign));

        return $campaign;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $campaign = $this->find($id);
        $campaign->update($data);

        event(new Updated($campaign));

        return $campaign;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $campaign = $this->find($id);

        event(new Deleted($campaign));

        return $campaign->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function lists($column = 'name', $key = 'id')
    {
        return Campaign::pluck($column, $key);
    }
}
