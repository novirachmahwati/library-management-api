<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ApiResource;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @OA\Get(
     *     path="/authors",
     *     summary="Get all authors",
     *     tags={"Authors"},
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter results by string. Search author name.",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="List of authors",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/author")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Cache key based on request parameters
        $cacheKey = 'authors_' . md5(json_encode($request->all()));

        // Retrieve authors from cache if available
        $authors = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            // Base query
            $query = Author::query();

            // Apply filters
            if ($request->has('filter')) {
                $query->where('name', 'like', '%' . $request->input('filter') . '%');
            }

            // Apply ordering
            $orderBy = $request->input('order_by', 'id');
            $orderDirection = $request->input('order_direction', 'asc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginate results
            $perPage = $request->input('per_page', 10);
            $authors = $query->paginate($perPage);

            return $authors;
        });

        return new ApiCollection($authors);
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
     *     path="/authors",
     *     summary="Create a new author",
     *     tags={"Authors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/authorEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Author created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/author"
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate(Author::rules());
        $author = new Author();
        $author->fill($request->input());

        $author->saveOrFail();

        return new ApiResource($author);
    }

    /**
     * @OA\Get(
     *      path="/authors/{id}",
     *      operationId="getAuthorById",
     *      tags={"Authors"},
     *      summary="Get author by ID",
     *      description="Returns a single author",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the author to get",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/author")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Data not found"
     *      )
     * )
     */
    public function show(Author $author)
    {
        return new ApiResource($author);
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
     *      path="/authors/{id}",
     *      operationId="updateAuthor",
     *      tags={"Authors"},
     *      summary="Update an existing author",
     *      description="Returns updated author data",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the author to update",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/authorEditable")
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
    public function update(Author $author, Request $request)
    {
        $request->validate(Author::rules($author));
        $fields = $request->json()->all();
        $author->fill($fields);

        $author->saveOrFail();

        return response([], 204);
    }

    /**
     * @OA\Delete(
     *      path="/authors/{id}",
     *      operationId="deleteAuthor",
     *      tags={"Authors"},
     *      summary="Delete an existing author",
     *      description="Deletes a author and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the author to delete",
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
    public function destroy(Author $author)
    {
        $author->delete();

        return response([], 204);
    }

    /**
     * @OA\Get(
     *      path="/authors/{id}/books",
     *      operationId="getBooksByAuthor",
     *      tags={"Associations"},
     *      summary="Retrieve books by author",
     *      description="Returns a list of books written by a specific author",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the author",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/book")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Author not found"
     *      )
     * )
     */
    public function getBooksByAuthor($id)
    {
        $author = Author::findOrFail($id);

        // Retrieve books associated with the author
        $books = $author->books()->get()->toArray();

        return response()->json($books, 200);
    }
}
