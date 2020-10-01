<?php

namespace App\Products\Controller;


use App\Products\Controller\Output\Request;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use App\Products\ProductNotFound;
use App\Products\Storage;


final class DeleteProduct
{
    /**
     * @var Storage $storage
     */
    private $storage;

    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage->delete((int)$id)
            ->then(
                function () {
                    $response = [
                        'request' => Request::createProduct(),
                    ];
                    return JsonResponse::ok($response);
                }
            )
            ->otherwise(
                function (ProductNotFound $error) {
                    return JsonResponse::notFound();
                }
            );
    }
}
