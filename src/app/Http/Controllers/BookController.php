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
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Get a paginated list of books",
     *     description="Fetch a paginated list of books with details like total data, per page, current page, last page, and next page URL",
     *     operationId="getBooks",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="The page number to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Books retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="index"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_data", type="integer", example=500),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=25),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost:8004/api/books?page=2"),
     *                 @OA\Property(
     *                     property="result",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Prof."),
     *                         @OA\Property(property="description", type="string", example="Alice. 'Of course twinkling begins with an air of great dismay..."),
     *                         @OA\Property(property="publish_date", type="string", format="date", example="2002-12-22"),
     *                         @OA\Property(property="author_id", type="integer", example=33)
     *                     )
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
    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     description="Create a new book entry in the database",
     *     operationId="createBook",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Filosofi Teras"),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Kita memiliki kebiasaan membesar-besarkan kesedihan. ..."
     *             ),
     *             @OA\Property(property="publish_date", type="string", format="date", example="2018-01-01"),
     *             @OA\Property(property="author_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="string", example="201"),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="store"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="title", type="string", example="Filosofi Teras"),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="Kita memiliki kebiasaan membesar-besarkan kesedihan. ..."
     *                 ),
     *                 @OA\Property(property="publish_date", type="string", format="date", example="2018-01-01"),
     *                 @OA\Property(property="author_id", type="integer", example=3),
     *                 @OA\Property(property="id", type="integer", example=502)
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
    public function store(Request $request)
    {
        try {
            $books = (new AddUpdateBookService($request))->call();
            if ($books->status() != 200) {
                return $this->error($books->message());
            }
            return $this->success($books->data(), $books->message(), __FUNCTION__, 201, 201);
        } catch (\Throwable $th) {
            $this->logError($th, "add book");
            return $this->error($th->getMessage(), __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Get details of a specific book",
     *     description="Fetch the details of a specific book by its ID, including associated author information",
     *     operationId="getBookById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="The ID of the book to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="show"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Dr."),
     *                 @OA\Property(property="description", type="string", example="Long Tale They were just beginning to end..."),
     *                 @OA\Property(property="publish_date", type="string", format="date", example="2003-10-05"),
     *                 @OA\Property(property="author_id", type="integer", example=29),
     *                 @OA\Property(
     *                     property="author",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=29),
     *                     @OA\Property(property="name", type="string", example="Gonzalo Strosin"),
     *                     @OA\Property(property="bio", type="string", example="I chose,' the Duchess sneezed occasionally..."),
     *                     @OA\Property(property="birth_date", type="string", format="date", example="2011-08-08")
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
    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update details of a specific book",
     *     description="Update the title, description, and publish date of a specific book by its ID",
     *     operationId="updateBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="The ID of the book to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Filosofi Teras"),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Kita memiliki kebiasaan membesar-besarkan kesedihan. ..."
     *             ),
     *             @OA\Property(property="publish_date", type="string", format="date", example="2018-02-02")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="update"),
     *             @OA\Property(property="data", type="integer", example=1)
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
    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     description="Delete a book by its ID",
     *     operationId="deleteBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the book to be deleted"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="text", type="string", example="success"),
     *             @OA\Property(property="method", type="string", example="destroy"),
     *             @OA\Property(property="data", type="integer", example=1)
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
