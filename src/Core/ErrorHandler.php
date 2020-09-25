<?php


namespace App\Core;


use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use \Throwable;


final class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (Throwable $error) {
            echo 'Error: ', $error->getTraceAsString(), PHP_EOL;
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => $error->getMessage()])
            );
        }
    }
}
