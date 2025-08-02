<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

class Rent extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'rent_date',
        'return_date',
        'actual_return_date',
    ];

    protected $casts = [
        'rent_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $code = '';
            for ($i = 0; $i < 10; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }
            $model->code = $code;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rentItems()
    {
        return $this->hasMany(RentItem::class);
    }

    public function getLateBarangsAttribute()
    {
        return $this->return_date->diffInDays($this->actual_return_date);
    }

    public function getLostBarangsAttribute()
    {
        return $this->rentItems()->where('is_lost', true)->count();
    }
}
