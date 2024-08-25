<?php

namespace App\Services\Book;

use App\Base\ServiceBase;
use App\Models\Book;
use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class AddUpdateBookService extends ServiceBase
{
    protected $bookRepo;
    protected $redisRepo;
    protected Request $request;
    protected ?int $bookId;
    protected $results;

    public function __construct(Request $request)
    {
        $this->bookRepo = new Repository(new Book());
        $this->redisRepo = new RedisRepository(new Redis());
        $this->request = $request;
        $this->bookId = null;
        $this->results = null;
    }

    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
        return $this;
    }

    public function call(): ServiceResponse
    {
        try {
            if ($this->validation()->fails()) {
                return self::error($this->validation(), $this->validation()->errors()->first());
            }

            if ($this->bookId) {
                $checkBook = $this->bookRepo->condition(['id' => $this->bookId], true);
                if (!$checkBook) {
                    return self::error(null, 'book not found');
                }
                $this->results = $this->bookRepo->update($this->bookId, $this->validation()->validated());
                $this->redisRepo->deleteRedisData($this->request->author_id ?? $checkBook->author_id);
            } else {
                $this->results = $this->bookRepo->store($this->validation()->validated());
                $this->redisRepo->deleteRedisData($this->request->author_id);
            }
            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }

    private function validation()
    {
        $validation = [
            'title' => 'string|max:255',
            'description' => 'string',
            'publish_date' => 'date_format:Y-m-d',
            'author_id' => 'exists:authors,id',
        ];

        if (!$this->bookId) {
            foreach ($validation as $key => $value) {
                $validation[$key] = $value.'|required';
            }
        }
        return Validator::make($this->request->all(), $validation);
    }
}