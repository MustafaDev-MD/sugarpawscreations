<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'has_before_image',
    ];

    /**
     * @return HasMany<Portfolio, $this>
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category): void {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category): void {
            $category->slug = Str::slug($category->name);
        });
    }
}