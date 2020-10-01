<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Orders\Storage;
use App\Orders\Order;


final class CreateOrder
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
        $productId = (int)$request->getParsedBody()['productId'];
        $quantity = (int)$request->getParsedBody()['quantity'];
        return $this->storage->create($productId, $quantity)
            ->then(
                function (Order $order) {
                    return JsonResponse::created($order);
                }
            );
    }
}
