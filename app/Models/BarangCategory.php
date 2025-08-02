<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangCategory extends Model
{
    protected $fillable = [
        'barang_id',
        'category_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
