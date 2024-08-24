<?php

namespace App\Services\Author;

use App\Base\ServiceBase;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Support\Facades\DB;

class DeleteAuthorService extends ServiceBase
{
    protected $authorRepo;
    protected $bookRepo;
    protected $results;

    public function __construct(protected int $authorId)
    {
        $this->authorRepo = new Repository(new Author());
        $this->bookRepo = new Repository(new Book());
        $this->results = null;
    }

    public function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $deleteBooks = $this->bookRepo->deleteByCondition(['author_id' => $this->authorId]);
            if ($deleteBooks) {
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