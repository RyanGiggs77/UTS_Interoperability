<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'username', 'roles', 'address',
        'city_id', 'province_id', 'phone', 'avatar', 'status'
    ];
}
