<?php

namespace App\Services\Author;

use App\Base\ServiceBase;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DeleteAuthorService extends ServiceBase
{
    protected $authorRepo;
    protected $redisRepo;
    protected $bookRepo;
    protected $results;

    public function __construct(protected int $authorId)
    {
        $this->authorRepo = new Repository(new Author());
        $this->redisRepo = new RedisRepository(new Redis());
        $this->bookRepo = new Repository(new Book());
        $this->results = null;
    }

    public function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            // Delete books data by author
            $deleteBooks = $this->bookRepo->deleteByCondition(['author_id' => $this->authorId]);

            // Delete redis data by author
            $deleteRedisData = $this->redisRepo->deleteRedisData($this->authorId);

            // Delete author after success delete books and redis data
            if ((bool)$deleteBooks && (bool)$deleteRedisData) {
                $this->results = $this->authorRepo->delete($this->authorId);
            }
            DB::commit();
            return self::success($this->results);
        } catch (\Throwable $th) {
            DB::rollBack();
            return self::catchError($th, $th->getMessage());
        }
    }
}