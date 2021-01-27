<?php

namespace ThaoHR\Repositories\Campaign;

use ThaoHR\Campaign;

interface CampaignRepository
{
    /**
     * Get all system campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();
    
    /**
     * Get all system campaigns with number of users for each sale stage.
     *
     * @return mixed
     */
    public function paginate($perPage, $search);
    
    /**
     * Find system sale stage by id.
     *
     * @param $id Campaign Id
     * @return Campaign|null
     */
    public function find($id);
    
    
    /**
     * Find item by code
     *
     * @param $code
     * @return mixed
     */
    public function findByCode($code);
    
    /**
     * Find item by name
     *
     * @param $name
     * @return mixed
     */
    public function findByName($name);
    
    /**
     * Create new system sale stage.
     *
     * @param array $data
     * @return Campaign
     */
    public function create(array $data);
    
    /**
     * Update specified sale stage.
     *
     * @param $id int Id
     * @param array $data
     * @return Campaign
     */
    public function update($id, array $data);
    
    /**
     * Remove sale stage from repository.
     *
     * @param $id int Id
     * @return bool
     */
    public function delete($id);
    /**
     * Create $key => $value array for all available campaigns.
     *
     * @param string $column
     * @param string $key
     * @return mixed
     */
    public function lists($column = 'name', $key = 'id');
}
