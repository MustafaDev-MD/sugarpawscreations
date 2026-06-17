<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Portfolio;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
        $category->slug = Str::slug($category->name);
    });

    static::updating(function ($category) {
        $category->slug = Str::slug($category->name);
    });
    }
}
