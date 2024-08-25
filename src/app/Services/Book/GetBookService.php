<?php

namespace App\Services\Book;

use App\Base\ServiceBase;
use App\Models\Book;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;

class GetBookService extends ServiceBase
{
    protected $bookRepo;
    protected ?int $bookId;
    protected $results;

    public function __construct()
    {
        $this->bookRepo = new Repository(new Book());
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
            if ($this->bookId) {
                $this->results = $this->bookRepo->showWithRelation($this->bookId, ['author']);
            } else {
                $books = $this->bookRepo->paginate(20);
                $this->mappingPaginate($books);
            }
            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }

    private function mappingPaginate($data)
    {
        $this->results = [
            'total_data' => $data->total(),
            'per_page' => intval ($data->perPage()),
            'current_page' => $data->currentPage(),
            'last_page' => $data->LastPage(),
            'next_page_url' => urldecode($data->nextPageUrl()),
            'result' => $data->toArray()['data']
        ];
    }
}