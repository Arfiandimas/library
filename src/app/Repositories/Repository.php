<?php

namespace App\Repositories;

/**
 * -----------------------------------------
 * Repository
 * -----------------------------------------
 * Please implements all depedency class 
 * using this interface
 * 
 */

class Repository implements RepositoryInterface
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * -----------------------------------------
     * Select all
     * -----------------------------------------
     * @return collection
     * 
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * -----------------------------------------
     * Select all
     * -----------------------------------------
     * @param int $perpage
     * 
     * @return collection
     * 
     */
    public function paginate($perpage)
    {
        return $this->model->paginate($perpage);
    }

    /**
     * -----------------------------------------
     * Select One
     * -----------------------------------------
     * @param int $id
     * 
     * @return document
     * 
     */
    public function show($id)
    {
        return $this->model->whereId($id)->first();
    }

    /**
     * -----------------------------------------
     * Select One
     * -----------------------------------------
     * @param int $id
     * @param array $relations
     * 
     * @return document
     * 
     */
    public function showWithRelation($id, $relations)
    {
        return $this->model->with($relations)->whereId($id)->first();
    }

    /**
     * -----------------------------------------
     * Select by some rule
     * -----------------------------------------
     * @param array $rules
     * @param boolean $first
     * 
     * @return document|collection
     * 
     */
    public function condition($rules, $first = false)
    {
        if (is_bool($first)) {
            if ($first)
                return $this->model->where($rules)->first();
            else
                return $this->model->where($rules)->get();
        }
        else
            throw new \Exception ('Bad in second parameter, please ensure the second variable has bool value. Set true if you wanna get first record instead of all record. The default is false');
    }

    /**
     * -----------------------------------------
     * Insert single record to table
     * -----------------------------------------
     * @param array $request
     * 
     * @return document
     * 
     */
    public function store($request)
    {
        return $this->model->create($request);
    }

    /**
     * -----------------------------------------
     * Update record to table
     * -----------------------------------------
     * @param int $id
     * @param array $request
     * 
     * @return bool
     * 
     */
    public function update($id, $request)
    {
        return $this->model->whereId($id)->update($request);
    }

    /**
     * -----------------------------------------
     * Delete record
     * -----------------------------------------
     * @param int $id
     * 
     * @return bool
     * 
     */
    public function delete($id)
    {
        return $this->model->whereId($id)->delete();
    }

    /**
     * -----------------------------------------
     * Delete record by condition
     * -----------------------------------------
     * @param array $condition
     * 
     * @return bool
     * 
     */
    public function deleteByCondition($condition)
    {
        return $this->model->where($condition)->delete();
    }
}
