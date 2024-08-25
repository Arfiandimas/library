<?php

namespace App\Services\Author;

use App\Base\ServiceBase;
use App\Models\Book;
use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Support\Facades\Redis;

class AuthorBookService extends ServiceBase
{
    protected $bookRepo;
    protected $redisRepo;
    protected $results;

    public function __construct(protected int $authorId)
    {
        $this->bookRepo = new Repository(new Book());
        $this->redisRepo = new RedisRepository(new Redis());
        $this->results = [];
    }

    public function call(): ServiceResponse
    {
        try {
            $getRedisData = $this->redisRepo->getRedisData($this->authorId);
            if (!$getRedisData) {
                $this->storeRedis();
            } else {
                $this->results = json_decode($getRedisData);
            }

            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }

    private function storeRedis()
    {
        $getDatabase = $this->bookRepo->condition(['author_id' => $this->authorId], false);
        if ($getDatabase->count() > 0) {
            $this->results = $getDatabase;
            $this->redisRepo->setRedisData($this->authorId, json_encode($getDatabase->toArray()));
        }
    }
}