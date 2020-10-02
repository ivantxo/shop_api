<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use App\Orders\Controller\Output\Request;
use App\Orders\OrderNotFound;
use App\Orders\Storage;
use Psr\Http\Message\ServerRequestInterface;


final class DeleteOrder
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
        return $this->storage->delete((int)$id)
            ->then(
                function () use ($id) {
                    return JsonResponse::ok(['request' => Request::deleteOrder((int)$id)]);
                }
            )
            ->otherwise(
                function (OrderNotFound $error) {
                    return JsonResponse::notFound();
                }
            );
    }
}
