<?php

namespace App\Http\Controllers;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Library Management System API",
 *         description="",
 *         @OA\Contact(
 *             email="novirachmahwati@gmail.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
*     ),
 *     @OA\Components(
 *         @OA\Parameter(
 *             name="filter",
 *             in="query",
 *             description="Filter results by string. Searches Name, Description, and Status. Status must match exactly. Others can be a substring.",
 *             @OA\Schema(type="string"),
 *         ),
 *         @OA\Parameter(
 *             parameter="order_by",
 *             name="order_by",
 *             in="query",
 *             description="Field to order results by",
 *             @OA\Schema(type="string"),
 *         ),
 *         @OA\Parameter(
 *             parameter="order_direction",
 *             name="order_direction",
 *             in="query",
 *             @OA\Schema(type="string", enum={"asc", "desc"}, default="asc"),
 *         ),
 *         @OA\Parameter(
 *             parameter="per_page",
 *             name="per_page",
 *             in="query",
 *             @OA\Schema(type="integer", default="10"),
 *         ),
 *         @OA\Parameter(
 *             parameter="page",
 *             name="page",
 *             in="query",
 *             @OA\Schema(type="integer", default="1"),
 *         ),
 *         @OA\Response(
 *           response=404,
 *           description="Not Found",
 *           @OA\JsonContent(@OA\Property(property="error", type="string"))
 *         ),
 *         @OA\Response(
 *           response=422,
 *           description="Unprocessable Entity",
 *           @OA\JsonContent(
 *              @OA\Property(property="message", type="string"),
 *              @OA\Property(property="errors", type="object"),
 *           )
 *         ),
 *     ),
 * )
 */
class OpenApiSpec
{
}
