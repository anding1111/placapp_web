<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nombre de la tabla en la base de datos.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * La clave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'name',
        'role',
        'level',
        'user_status',
        'online_status',
        'last_connection'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_status' => 'boolean',
        'online_status' => 'boolean',
        'last_connection' => 'datetime',
    ];

    /**
     * Indica si el modelo debe usar timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relación con UUIDs
     */
    public function uuids()
    {
        return $this->hasMany(Uuid::class, 'user_id');
    }
    
    /**
     * Determina si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->role <= 1;
    }
    
    /**
     * Determina si el usuario puede gestionar a otro usuario
     */
    public function canManage(User $targetUser)
    {
        return $this->level <= $targetUser->level;
    }
    
    /**
     * Determina si el usuario es un usuario demo
     */
    public function isDemoUser()
    {
        return $this->level == 4;
    }
}