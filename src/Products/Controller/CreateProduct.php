<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use App\Products\Product;
use App\Products\Storage;


final class CreateProduct
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
        $name = $request->getParsedBody()['name'];
        $price = $request->getParsedBody()['price'];

        return $this->storage->create($name, $price)
            ->then(
                function (Product $product) {
                    return JsonResponse::ok($product->toArray());
                }
            );
    }
}
