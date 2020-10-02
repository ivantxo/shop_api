<?php


namespace App\Orders\Controller;


use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;


final class Input
{
    /**
     * @var ServerRequestInterface $request
     */
    private $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function validate(): void
    {
        $productValidator = Validator::key(
            'productId',
            Validator::allOf(
                Validator::notBlank(),
                Validator::number(),
                Validator::positive()
            )
        )->setName('productId');

        $quantityValidator = Validator::key(
            'quantity',
            Validator::allOf(
                Validator::notBlank(),
                Validator::number(),
                Validator::positive()
            )
        )->setName('quantity');
        Validator::allOf(
            $productValidator,
            $quantityValidator
        )->assert($this->request->getParsedBody());
    }

    public function productId(): int
    {
        return (int)$this->request->getParsedBody()['productId'];
    }

    public function quantity(): int
    {
        return (int)$this->request->getParsedBody()['quantity'];
    }
}
