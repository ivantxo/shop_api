<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


final class CreateProduct
{
    public function __invoke(ServerRequestInterface $request)
    {
        return JsonResponse::ok(
            ['message' => 'POST request to /products']
        );
    }
}
