<?php


namespace App\Orders;


use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;


final class Storage
{
    /**
     * @var ConnectionInterface $connection
     */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $productId, int $quantity): PromiseInterface
    {
        return $this->connection
            ->query(
                'INSERT INTO orders (product_id, quantity) VALUES (?, ?)', [$productId, $quantity]
            )
            ->then(
                function (QueryResult $result) use ($productId, $quantity) {
                    return new Order(
                        $result->insertId,
                        $productId,
                        $quantity
                    );
                }
            );
    }
}
