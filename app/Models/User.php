<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'level',
        'user_status',
        'online_status',
        'last_connection'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'user_status' => 'boolean',
        'online_status' => 'boolean',
        'last_connection' => 'datetime',
    ];

    public function uuids()
    {
        return $this->hasMany(Uuid::class);
    }

    public function isAdmin()
    {
        return $this->role == 1;
    }
}