<?php


namespace App\Products;


final class Product
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var float $price
     */
    public $price;

    /**
     * @var string|null $image
     */
    public $image;

    public function __construct(int $id, string $name, float $price, ?string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }
}
