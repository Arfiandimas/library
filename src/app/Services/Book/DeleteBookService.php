<?php

namespace App\Services\Book;

use App\Base\ServiceBase;
use App\Models\Book;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;

class DeleteBookService extends ServiceBase
{
    protected $bookRepo;
    protected $results;

    public function __construct(protected int $bookId)
    {
        $this->bookRepo = new Repository(new Book());
        $this->results = null;
    }

    public function call(): ServiceResponse
    {
        try {
            $this->results = $this->bookRepo->delete($this->bookId);
            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }
}