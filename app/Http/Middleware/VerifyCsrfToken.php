<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/', // Excluye la ruta de login (GET y POST)
        'login', // Alternativa usando el nombre de ruta
        'api/*', // Si tienes rutas API
    ];
}