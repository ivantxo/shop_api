<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


final class CreateProduct
{
    public function __invoke(ServerRequestInterface $request)
    {
        $product = [
            'name' => $request->getParsedBody()['name'],
            'price' => $request->getParsedBody()['price'],
        ];
        return JsonResponse::ok([
            'message' => 'POST request to /products',
            'product' => $product,
        ]);
    }
}
