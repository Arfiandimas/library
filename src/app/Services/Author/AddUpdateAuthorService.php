<?php

namespace App\Services\Author;

use App\Base\ServiceBase;
use App\Models\Author;
use App\Repositories\Repository;
use App\Responses\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddUpdateAuthorService extends ServiceBase
{
    protected $authorRepo;
    protected Request $request;
    protected ?int $authorId;
    protected $results;

    public function __construct(Request $request)
    {
        $this->authorRepo = new Repository(new Author());
        $this->request = $request;
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
            if ($this->validation()->fails()) {
                return self::error($this->validation(), $this->validation()->errors()->first());
            }

            if ($this->authorId) {
                $checkAuthor = $this->authorRepo->condition(['id' => $this->authorId], true);
                if (!$checkAuthor) {
                    return self::error(null, 'author not found');
                }
                $this->results = $this->authorRepo->update($this->authorId, $this->validation()->validated());
            } else {
                $this->results = $this->authorRepo->store($this->validation()->validated());
            }
            return self::success($this->results);
        } catch (\Throwable $th) {
            return self::catchError($th, $th->getMessage());
        }
    }

    private function validation()
    {
        $validation = [
            'name' => 'string|max:255',
            'bio' => 'string|max:255',
            'birth_date' => 'date_format:Y-m-d',
        ];

        if (!$this->authorId) {
            foreach ($validation as $key => $value) {
                $validation[$key] = $value.'|required';
            }
        }
        return Validator::make($this->request->all(), $validation);
    }
}