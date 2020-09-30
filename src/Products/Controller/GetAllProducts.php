<?php


namespace App\Products\Controller;


use App\Products\Product;
use App\Products\Storage;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use App\Products\Controller\Output\Product as Output;
use App\Products\Controller\Output\Request;


final class GetAllProducts
{
    /**
     * @var Storage $storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->storage->getAll()
            ->then(
                function (array $products) {
                    $response = [
                        'products' => $this->mapProducts(...$products),
                        'count' => count($products)
                    ];
                    return JsonResponse::ok($response);
                }
            );
    }

    private function mapProducts(Product ...$products): array
    {
        return array_map(
            function (Product $product) {
                return Output::fromEntity(
                    $product,
                    Request::detailedProduct($product->id)
                );
            },
            $products
        );
    }
}
