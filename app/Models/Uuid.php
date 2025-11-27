<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uuid extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uuids';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'status',
    ];

    /**
     * Get the user that owns the UUID.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si un UUID está autorizado para un usuario específico.
     *
     * @param int $userId
     * @param string $uuid
     * @return bool
     */
    public static function isAuthorized($userId, $uuid)
    {
        return self::where('user_id', $userId)
                  ->where('uuid', $uuid)
                  ->where('status', 1)
                  ->exists();
    }
}
