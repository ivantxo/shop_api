<?php


namespace App\Orders\Controller\Output;


use App\Orders\Order as OrderEntity;


final class Order
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var int $productId
     */
    public $productId;

    /**
     * @var int $quantity
     */
    public $quantity;

    /**
     * @var Request $request
     */
    public $request;

    public function __construct(int $id, int $productId, int $quantity, Request $request)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->request = $request;
    }

    public static function fromEntity(OrderEntity $order, Request $request): self
    {
        return new self(
            $order->id,
            $order->productId,
            $order->quantity,
            $request
        );
    }
}
