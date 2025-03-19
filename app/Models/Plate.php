<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_name',
        'plate_desc',
        'plate_entry_date',
        'plate_exit_date',
        'plate_enable',
        'plate_level',
        'plate_location',
        'plate_detail'
    ];

    protected $casts = [
        'plate_entry_date' => 'datetime',
        'plate_exit_date' => 'datetime',
        'plate_enable' => 'boolean',
    ];
}