<?php

namespace ThaoHR\Repositories\SaleStage;

use ThaoHR\SaleStage;

interface SaleStageRepository
{
    /**
     * Get all system sale stages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();
    
    /**
     * Get all system sale stages with number of users for each role.
     *
     * @return mixed
     */
    public function paginate($perPage, $search);
    
    /**
     * Find system role by id.
     *
     * @param $id SaleStage Id
     * @return SaleStage|null
     */
    public function find($id);
    
    /**
     * Find item by name
     *
     * @param $name
     * @return mixed
     */
    public function findByName($name);
    
    /**
     * Create new system role.
     *
     * @param array $data
     * @return SaleStage
     */
    public function create(array $data);
    
    /**
     * Update specified role.
     *
     * @param $id int Id
     * @param array $data
     * @return SaleStage
     */
    public function update($id, array $data);
    
    /**
     * Remove role from repository.
     *
     * @param $id SaleStage Id
     * @return bool
     */
    public function delete($id);
    /**
     * Create $key => $value array for all available sale stages.
     *
     * @param string $column
     * @param string $key
     * @return mixed
     */
    public function lists($column = 'name', $key = 'id');

}
