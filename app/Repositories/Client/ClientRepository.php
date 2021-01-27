<?php

namespace ThaoHR\Repositories\Client;

use ThaoHR\Client;

interface ClientRepository
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
    public function paginate($perPage, array $params);
    
    /**
     * Find system role by id.
     *
     * @param $id Client Id
     * @return Client|null
     */
    public function find($id);
    
    /**
     * Find system client by phone.
     *
     * @param $phone Client email
     * @return Client|null
     */
    public function findByPhone($phone);
    
    
    /**
     * Number of client in database.
     *
     * @return mixed
     */
    public function count();
    
    /**
     * Count client by user id
     * @param int $userId
     */
    public function countClientBy($userId);
    
    /**
     * Number of client not called in database.
     *
     * @return mixed
     */
    public function countCalled();
    
    /**
     * Count client by user id
     * @param int $userId
     */
    public function countClientCalled($userId);
    
    /**
     * Number of client in database.
     *
     * @return mixed
     */
    public function countNotCalled();
    
    /**
     * Count client by user id
     * @param int $userId
     */
    public function countClientNotCalled($userId);
    
    /**
     * Number of client not answer in database.
     *
     * @return mixed
     */
    public function countNotAnswer();
    
    /**
     * Count client by user id
     * @param int $userId
     */
    public function countClientNotAnswer($userId);
    
    
    /**
     * Create new system role.
     *
     * @param array $data
     * @return Client
     */
    public function create(array $data);
    
    /**
     * Update specified role.
     *
     * @param $id int Id
     * @param array $data
     * @return Client
     */
    public function update($id, array $data);
    
    /**
     * Remove role from repository.
     *
     * @param $id Client Id
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
    
    
    /**
     * Get all client histories
     * @param $id Client Id
     * @return mixed
     */
    public function clientHistories($clientId);
}
