<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Book;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ApiResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @OA\Get(
     *     path="/books",
     *     summary="Get all books",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter results by string. Search book title.",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="List of books",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/book")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Cache key based on request parameters
        $cacheKey = 'books_' . md5(json_encode($request->all()));

        // Retrieve books from cache if available
        $books = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            // Base query
            $query = Book::query();

            // Apply filters
            if ($request->has('filter')) {
                $query->where('title', 'like', '%' . $request->input('filter') . '%');
            }

            // Apply ordering
            $orderBy = $request->input('order_by', 'id');
            $orderDirection = $request->input('order_direction', 'asc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginate results
            $perPage = $request->input('per_page', 10);
            $books = $query->paginate($perPage);

            return $books;
        });

        return new ApiCollection($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/books",
     *     summary="Create a new book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/bookEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/book"
     *         )
     *     ),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function store(Request $request)
    {
        $request->validate(Book::rules());
        $book = new Book();
        $book->fill($request->input());

        $book->saveOrFail();

        return new ApiResource($book);
    }

    /**
     * @OA\Get(
     *      path="/books/{id}",
     *      operationId="getBookById",
     *      tags={"Books"},
     *      summary="Get book by ID",
     *      description="Returns a single book",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to get",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/book")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Data not found"
     *      )
     * )
     */
    public function show(Book $book)
    {
        return new ApiResource($book);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *      path="/books/{id}",
     *      operationId="updateBook",
     *      tags={"Books"},
     *      summary="Update an existing book",
     *      description="Returns updated book data",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to update",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/bookEditable")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Data not found"
     *      ),
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function update(Book $book, Request $request)
    {
        $request->validate(Book::rules($book));
        $fields = $request->json()->all();
        $book->fill($fields);

        $book->saveOrFail();

        return response([], 204);
    }

    /**
     * @OA\Delete(
     *      path="/books/{id}",
     *      operationId="deleteBook",
     *      tags={"Books"},
     *      summary="Delete an existing book",
     *      description="Deletes a book and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the book to delete",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Data not found"
     *      )
     * )
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response([], 204);
    }
}
