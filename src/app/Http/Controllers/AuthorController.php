<?php

namespace App\Http\Controllers;

use App\Services\Author\AddUpdateAuthorService;
use App\Services\Author\DeleteAuthorService;
use App\Services\Author\GetAuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $authors = (new GetAuthorService())->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "get author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $authors = (new AddUpdateAuthorService($request))->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "add author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $authors = (new GetAuthorService())->setAuthodId($id)->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "show author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $authors = (new AddUpdateAuthorService($request))->setAuthodId($id)->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "update author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $authors = (new DeleteAuthorService($id))->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "delete author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }
}
