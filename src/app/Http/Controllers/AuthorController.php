<?php

namespace App\Http\Controllers;

use App\Services\Author\AddUpdateAuthorService;
use App\Services\Author\AuthorBookService;
use App\Services\Author\DeleteAuthorService;
use App\Services\Author\GetAuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"Author"},
     *     summary="Get List Author Data",
     *     description="Fetch author's data with paginate 20",
     *     operationId="author",
     *     @OA\Response(
     *         response="200",
     *         description="Get Author",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "code": 200,
     *                 "text": "success",
     *                 "method": "index",
     *                 "data": {
     *                     "total_data": 50,
     *                     "per_page": 20,
     *                     "current_page": 1,
     *                     "last_page": 3,
     *                     "next_url": "http://localhost:8004/api/authors?page=2",
     *                     "result": {{
     *                         "id": "1",
     *                         "name": "Mr. Enrique Hand",
     *                         "bio": "Just as she wandered about for a good opportunity for making her escape; so she set to work throwing everything within her reach at the bottom of a procession,' thought she, 'if people had all to lie down upon their faces. There was no label this time.",
     *                         "birth_date": "2008-02-02"
     *                     }}
     *                 }
     *             }
     *         )
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/api/authors",
     *     tags={"Author"},
     *     summary="Create a new author",
     *     description="Add a new author to the database",
     *     operationId="createAuthor",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Henry Manampiring"),
     *             @OA\Property(property="bio", type="string", example="Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger."),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1970-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Author created",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "status": true,
     *                 "code": 201,
     *                 "text": "success",
     *                 "method": "store",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Henry Manampiring",
     *                     "bio": "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
     *                     "birth_date": "1970-01-01"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         @OA\Schema(type="string"),
     *         description="Expected response format"
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         @OA\Schema(type="string"),
     *         description="Content type of the request body"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $authors = (new AddUpdateAuthorService($request))->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__, 201, 201);
        } catch (\Throwable $th) {
            $this->logError($th, "add author");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/authors/{id}",
     *     tags={"Author"},
     *     summary="Get a specific author by ID",
     *     description="Fetch a specific author's data by ID",
     *     operationId="getAuthorById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of the author"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show Author",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "status": true,
     *                 "code": 200,
     *                 "text": "success",
     *                 "method": "show",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Mr. Enrique Hand",
     *                     "bio": "Just as she wandered about for a good opportunity for making her escape; so she set to work throwing everything within her reach at the bottom of a procession,' thought she, 'if people had all to lie down upon their faces. There was no label this time.",
     *                     "birth_date": "2008-02-02"
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $authors = (new GetAuthorService())->setAuthorId($id)->call();
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
    /**
     * @OA\Put(
     *     path="/api/authors/{id}",
     *     tags={"Author"},
     *     summary="Update an existing author",
     *     description="Update the details of an existing author by ID",
     *     operationId="updateAuthor",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of the author to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Arfian Dimas Andi Permana"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="2000-12-26")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Author updated",
     *         @OA\JsonContent(
     *             type="object",
     *            example={
     *                 "status": true,
     *                 "code": 200,
     *                 "text": "success",
     *                 "method": "update",
     *                 "data": 1
     *             }
     *         )
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         @OA\Schema(type="string"),
     *         description="Expected response format"
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         @OA\Schema(type="string"),
     *         description="Content type of the request body"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $authors = (new AddUpdateAuthorService($request))->setAuthorId($id)->call();
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
    /**
     * @OA\Delete(
     *     path="/api/authors/{id}",
     *     tags={"Author"},
     *     summary="Delete an author by ID",
     *     description="Remove an author from the database by their ID",
     *     operationId="deleteAuthor",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of the author to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Author deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "status": true,
     *                 "code": 200,
     *                 "text": "success",
     *                 "method": "destroy",
     *                 "data": 1
     *             }
     *         )
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         @OA\Schema(type="string"),
     *         description="Expected response format"
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         @OA\Schema(type="string"),
     *         description="Content type of the request body"
     *     )
     * )
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

    /**
     * Retrieve all books by a specific author.
     */
    /**
     * @OA\Get(
     *     path="/api/authors/{id}/books",
     *     tags={"Author"},
     *     summary="Get books by author ID",
     *     description="Fetch all books associated with a specific author by their ID",
     *     operationId="authorBooks",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of the author whose books to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Books retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="authorBooks"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=132),
     *                     @OA\Property(property="title", type="string", example="Prof."),
     *                     @OA\Property(property="description", type="string", example="This sounded promising"),
     *                     @OA\Property(property="publish_date", type="string", format="date", example="1992-04-20"),
     *                     @OA\Property(property="author_id", type="integer", example=5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         @OA\Schema(type="string"),
     *         description="Expected response format"
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         @OA\Schema(type="string"),
     *         description="Content type of the request body"
     *     )
     * )
     */
    public function authorBooks(int $id)
    {
        try {
            $authors = (new AuthorBookService($id))->call();
            if ($authors->status() != 200) {
                return $this->error($authors->message());
            }
            return $this->success($authors->data(), $authors->message(), __FUNCTION__);
        } catch (\Throwable $th) {
            $this->logError($th, "author books");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }
}
