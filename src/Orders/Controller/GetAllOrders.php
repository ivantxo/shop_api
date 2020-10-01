<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use App\Orders\Storage;
use Psr\Http\Message\ServerRequestInterface;
use App\Orders\Controller\Output\Order as Output;
use App\Orders\Controller\Output\Request;
use App\Orders\Order;


final class GetAllOrders
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
                function ($orders) {
                    $response = [
                        'orders' => $this->mapOrders(...$orders),
                        'count' => count($orders),
                    ];
                    return JsonResponse::ok($response);
                }
            );
    }

    private function mapOrders(Order ...$orders): array
    {
        return array_map(
            function (Order $order) {
                return Output::fromEntity(
                    $order,
                    Request::detailedOrder($order->id)
                );
            },
            $orders
        );
    }
}
