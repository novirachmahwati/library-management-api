<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The attributes that are mass assignable.
 * 
 * @var array
 * 
 * @OA\Schema(
 *   schema="authorEditable",
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="bio", type="string"),
 *   @OA\Property(property="birth_date", type="string", format="date"),
 * )
 * @OA\Schema(
 *   schema="author",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/authorEditable"),
 *     @OA\Schema(
 *       @OA\Property(property="id", type="integer", format="int64"),
 *       @OA\Property(property="created_at", type="string", format="date-time"),
 *       @OA\Property(property="updated_at", type="string", format="date-time"),
 *     ),
 *   },
 * )
 */

class Author extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name', 'bio', 'birth_date',
    ];

    /**
     * Get the books associated with the author.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date'
        ];
    }

}
