<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'laravel_session',  // Excluye la cookie de sesión
        'XSRF-TOKEN',       // Excluye el token CSRF
    ];
}