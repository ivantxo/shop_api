<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;
use Exception;
use App\Products\Product;
use App\Products\Storage;
use App\Products\Controller\Output\Product as Output;
use App\Products\Controller\Output\Request;
use App\Core\Uploader;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;


final class CreateProduct
{
    /**
     * @var Storage $storage
     */
    private $storage;

    /**
     * @var Uploader $uploader
     */
    private $uploader;

    public function __construct(Storage $storage, Uploader $uploader)
    {
        $this->storage = $storage;
        $this->uploader = $uploader;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();

        return $this->upload($input)
            ->then(
                function ($pathToImage) use ($input) {
                    return $this->storage->create(
                        $input->name(),
                        $input->price(),
                        $pathToImage
                    );
                }
            )
            ->then(
                function (Product $product) {
                    $response = [
                        'product' => Output::fromEntity(
                            $product, Request::detailedProduct($product->id)
                        ),
                    ];
                    return JsonResponse::ok($response);
                },
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }

    private function upload(Input $input): PromiseInterface
    {
        if ($input->image() === null)
            return resolve();
        return $this->uploader->upload($input->image());
    }
}
