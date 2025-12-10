<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateDemo extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos.
     *
     * @var string
     */
    protected $table = 'plates_demo';

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
        'plate_name',
        'plate_desc',
        'plate_entry_date',
        'plate_exit_date',
        'plate_enable',
        'plate_level',
        'plate_location',
        'plate_detail',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'plate_enable' => 'boolean',
        'plate_entry_date' => 'datetime',
        'plate_exit_date' => 'datetime',
    ];
}
