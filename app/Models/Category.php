<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    /**
     * @return HasMany<Portfolio>
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    protected static function boot()
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