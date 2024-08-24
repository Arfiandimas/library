<?php

namespace App\Services\Author;

use App\Base\ServiceBase;
use App\Models\Author;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;

class GetAuthorService extends ServiceBase
{
    protected $authorRepo;
    protected ?int $authorId;
    protected $results;

    public function __construct()
    {
        $this->authorRepo = new Repository(new Author());
        $this->authorId = null;
        $this->results = null;
    }

    public function setAuthodId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    public function call(): ServiceResponse
    {
        try {
            if ($this->authorId) {
                $this->results = $this->authorRepo->show($this->authorId);
            } else {
                $authors = $this->authorRepo->paginate(20);
                $this->mappingPaginate($authors);
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