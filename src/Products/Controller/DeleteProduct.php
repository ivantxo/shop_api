<?php

namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


final class DeleteProduct
{
    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return JsonResponse::ok(
            ['message' => "DELETE request to /products/{$id}"]
        );
    }
}
