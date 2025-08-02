<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = (string) Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = (string) Str::slug($model->name);
        });
    }

    public function barangCategories()
    {
        return $this->hasMany(BarangCategory::class);
    }
}
