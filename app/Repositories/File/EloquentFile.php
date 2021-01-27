<?php

namespace ThaoHR\Repositories\File;

use ThaoHR\Events\File\Created;
use ThaoHR\Events\File\Deleted;
use ThaoHR\Events\File\Updated;
use ThaoHR\File;

class EloquentFile implements FileRepository
{
    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null, $status = null)
    {
        $query = File::query();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', "like", "%{$search}%");
            });
        }
        
        $result = $query->orderBy('id', 'desc')
        ->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        if ($status) {
            $result->appends(['status' => $status]);
        }
        
        return $result;
    }
    
    /**
     * {@inheritdoc}
     */
    public function list(array $params)
    {
        $query = File::query();
        
        $keyword = !empty($params['keyword'])?$params['keyword'] : null;
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', "like", "%{$keyword}%");
            });
        }
        
        $limit = !empty($params['limit'])?$params['limit'] : 20;
        $result = $query->orderBy('id', 'desc')
        ->paginate($limit);
        
        return $result;
    }
    
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return File::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return File::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $file = File::create($data);

        event(new Created($file));

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $file = $this->find($id);

        $file->update($data);

        event(new Updated($file));

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $file = $this->find($id);

        event(new Deleted($file));

        return $file->delete();
    }


    /**
     * {@inheritdoc}
     */
    public function lists($column = 'name', $key = 'id')
    {
        return File::pluck($column, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function findByHash($hash, $type)
    {
        return File::where('foreign_hash', $hash)->where('type', $type)->first();
    }
}
