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

    public function getAll(): PromiseInterface
    {
        return $this->connection
            ->query('SELECT id, product_id, quantity FROM orders')
            ->then(
                function (QueryResult $result) {
                    return array_map(
                        function (array $row) {
                            return $this->mapOrder($row);
                        },
                        $result->resultRows
                    );
                }
            );
    }

    private function mapOrder(array $row): Order
    {
        return new Order(
            (int)$row['id'],
            (int)$row['product_id'],
            (int)$row['quantity']
        );
    }
}
