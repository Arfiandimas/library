<?php

namespace App\Repositories\Child;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Redis;

class RedisRepository extends Repository
{
    /**
     * -----------------------------------------
     * Select data by key
     * -----------------------------------------
     * @param string $key
     * 
     * @return null|string
     * 
     */
    public function getRedisData(string $key)
    {
        return Redis::get($key);
    }

    /**
     * -----------------------------------------
     * Set data redis
     * -----------------------------------------
     * @param string $key
     * @param string $value
     * 
     * @return bool
     * 
     */
    public function setRedisData(string $key, string $value)
    {
        return Redis::set($key, $value);
    }

    /**
     * -----------------------------------------
     * Delete data redis
     * -----------------------------------------
     * @param string $key
     * 
     * @return bool
     * 
     */
    public function deleteRedisData(string $key)
    {
        return Redis::del($key);
    }
}