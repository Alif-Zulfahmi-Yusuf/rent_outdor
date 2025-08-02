<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function renting()
    {
        return $this->rents()->whereNull('actual_return_date')->get();
    }

    public function totalBarangs()
    {
        $total = 0;
        foreach ($this->rents as $rent) {
            $total += $rent->rentItems()->count();
        }
        return $total;
    }

    public function getTotalBookRentedAttribute()
    {
        $total = 0;
        foreach ($this->renting() as $rent) {
            $total += $rent->rentItems()->count();
        }
        return $total;
    }

    public function getTotalPinaltyAttribute()
    {
        $total = 0;
        foreach ($this->rents as $rent) {
            $total += $rent->pinalty;
        }
        return $total;
    }

    public function bags()
    {
        return $this->hasMany(Bag::class);
    }
}
