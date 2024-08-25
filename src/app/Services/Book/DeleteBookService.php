<?php

namespace App\Services\Book;

use App\Base\ServiceBase;
use App\Models\Book;
use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Support\Facades\Redis;

class DeleteBookService extends ServiceBase
{
    protected $bookRepo;
    protected $redisRepo;
    protected $results;

    public function __construct(protected int $bookId)
    {
        $this->bookRepo = new Repository(new Book());
        $this->redisRepo = new RedisRepository(new Redis());
        $this->results = null;
    }

    public function call(): ServiceResponse
    {
        try {
            $getBook = $this->bookRepo->condition(["id" => $this->bookId], true);
            if ($getBook) {
                $this->redisRepo->deleteRedisData($getBook->author_id);
                $this->results = $getBook->delete();
            }
            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }
}