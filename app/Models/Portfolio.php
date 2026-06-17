<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $before_image
 * @property string $after_image
 */
class Portfolio extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'before_image',
        'after_image',
    ];

    /**
     * @return BelongsTo<Category, Portfolio>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}