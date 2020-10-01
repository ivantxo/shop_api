<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use App\Orders\Storage;
use Psr\Http\Message\ServerRequestInterface;


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
                    return JsonResponse::ok($orders);
                }
            );
    }
}
