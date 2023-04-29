<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'region',
        'details',
        'latitude',
        'longitude',
        'notes',
    ];

        public function user()
    {
        return $this->hasOne(User::class);
    }

}