<?php

namespace App\Products\Controller;


use App\Products\Controller\Output\Request;
use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use App\Products\ProductNotFound;
use App\Products\Storage;


final class UpdateProduct
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
        $input = new Input($request);
        $input->validate();
        return $this->storage->update((int)$id, $input->name(), (float)$input->price())
            ->then(
                function () use ($id) {
                    $response = [
                        'request' => Request::detailedProduct((int)$id)
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
