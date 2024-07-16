<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * The attributes that are mass assignable.
 * 
 * @var array
 * 
 * @OA\Schema(
 *   schema="bookEditable",
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="publish_date", type="string", format="date"),
 *   @OA\Property(property="author_id", type="integer"),
 * )
 * @OA\Schema(
 *   schema="book",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/bookEditable"),
 *     @OA\Schema(
 *       @OA\Property(property="id", type="integer", format="int64"),
 *       @OA\Property(property="created_at", type="string", format="date-time"),
 *       @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *   },
 * )
 */
class Book extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'publish_date', 'author_id'
    ];

    /**
     * Get the author that owns the book.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'author_id' => [
                'required',
                'exists:authors,id',
            ],
        ];
    }
}
