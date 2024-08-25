<?php

namespace App\Http\Controllers;

use App\Services\Book\AddUpdateBookService;
use App\Services\Book\DeleteBookService;
use App\Services\Book\GetBookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $books = (new GetBookService())->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "get book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $books = (new AddUpdateBookService($request))->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "add book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $books = (new GetBookService())->setBookId($id)->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "show book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $books = (new AddUpdateBookService($request))->setBookId($id)->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "update book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $books = (new DeleteBookService($id))->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "delete book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }
}
