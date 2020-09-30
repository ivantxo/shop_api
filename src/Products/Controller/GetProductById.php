<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use App\Products\Product;
use App\Products\Storage;
use App\Products\ProductNotFound;
use App\Products\Controller\Output\Product as Output;
use App\Products\Controller\Output\Request;


final class GetProductById
{
    /**
     * @var Storage $storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage
            ->getById((int)$id)
            ->then(
                function (Product $product) {
                    $response = [
                        'product' => Output::fromEntity(
                            $product,
                            Request::updateProduct($product->id)
                        ),
                        'request' => Request::listOfProducts()
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
