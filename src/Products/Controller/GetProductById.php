<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


class GetProductById
{
    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return JsonResponse::ok(
            ['message' => "GET request to /products/{$id}"]
        );
    }
}
