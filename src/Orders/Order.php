<?php


namespace App\Orders;


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

    public function __construct(int $id, int $productId, int $quantity)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
