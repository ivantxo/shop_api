<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use Exception;
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
        $input = new Input($request);
        $input->validate();

        return $this->storage->create($input->name(), $input->price())
            ->then(
                function (Product $product) {
                    return JsonResponse::ok($product->toArray());
                },
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}
