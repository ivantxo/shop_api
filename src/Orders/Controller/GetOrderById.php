<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use App\Orders\Order;
use App\Orders\OrderNotFound;
use App\Orders\Storage;
use App\Orders\Controller\Output\Order as Output;
use App\Orders\Controller\Output\Request;
use Psr\Http\Message\ServerRequestInterface;


final class GetOrderById
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
        return $this->storage->getById((int)$id)
            ->then(
                function (Order $order) {
                    $response = [
                        'order' => Output::fromEntity(
                            $order,
                            Request::detailedOrder($order->id)
                        ),
                        'request' => Request::listOrders()
                    ];
                    return JsonResponse::ok($response);
                }
            )
            ->otherwise(
                function (OrderNotFound $error) {
                    return JsonResponse::notFound();
                }
            );
    }
}
