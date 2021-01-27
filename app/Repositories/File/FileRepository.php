<?php

namespace ThaoHR\Repositories\File;

use ThaoHR\Role;
use ThaoHR\File;

interface FileRepository
{
    /**
     * Paginate files.
     *
     * @param $perPage
     * @param null $search
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $search = null, $status = null);
    
    /**
     * Get all files.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Lists all files into $key => $column value pairs.
     *
     * @param string $column
     * @param string $key
     * @return mixed
     */
    public function lists($column = 'name', $key = 'id');

    /**
     * Find File by id.
     *
     * @param $id Role Id
     * @return File|null
     */
    public function find($id);

    /**
     * Find File by name:
     *
     * @param $hash
     * @return mixed
     */
    public function findByHash($hash, $type);

    /**
     * Create new File.
     *
     * @param array $data
     * @return File
     */
    public function create(array $data);

    /**
     * Update specified File.
     *
     * @param $id File Id
     * @param array $data
     * @return File
     */
    public function update($id, array $data);

    /**
     * Remove role from repository.
     *
     * @param $id Role Id
     * @return bool
     */
    public function delete($id);

}
