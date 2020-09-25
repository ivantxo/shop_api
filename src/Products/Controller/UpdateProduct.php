<?php

namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


class UpdateProduct
{
    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return JsonResponse::ok(
            ['message' => "PUT request to /products/{$id}"]
        );
    }
}
