<?php


namespace App\Core;


use Psr\Http\Message\ServerRequestInterface;
use \Throwable;


final class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (Throwable $error) {
            echo 'Error: ', $error->getTraceAsString(), PHP_EOL;
            return JsonResponse::internalServerError(
                $error->getMessage()
            );
        }
    }
}
