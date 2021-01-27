<?php

namespace ThaoHR\Repositories\SaleStage;

use ThaoHR\SaleStage;
use ThaoHR\Events\SaleStage\Created;
use ThaoHR\Events\SaleStage\Updated;
use ThaoHR\Events\SaleStage\Deleted;

class EloquentSaleStage implements SaleStageRepository
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return SaleStage::all();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null)
    {
        $query = SaleStage::query();

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
        return SaleStage::find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function findByName($name)
    {
        return SaleStage::where('name', $name)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $role = SaleStage::create($data);

        event(new Created($role));

        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $role = $this->find($id);

        $role->update($data);

        event(new Updated($role));

        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $role = $this->find($id);

        event(new Deleted($role));

        return $role->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function lists($column = 'name', $key = 'id')
    {
        return SaleStage::pluck($column, $key);
    }
}
