<?php
/* Guarda la petición para que los controladores usen $this->request(). */

declare(strict_types=1);

namespace App\Core;

final class RequestContext
{
    private static ?Request $request = null;

    public static function set(Request $request): void
    {
        self::$request = $request;
    }

    public static function get(): Request
    {
        if (self::$request === null) {
            self::$request = Request::fromGlobals();
        }

        return self::$request;
    }
}
