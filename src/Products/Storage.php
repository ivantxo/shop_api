<?php


namespace App\Products;


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

    public function create(string $name, float $price): PromiseInterface
    {
        return $this->connection
            ->query('INSERT INTO products (name, price) VALUES (?, ?)', [$name, $price])
            ->then(
                function (QueryResult $result) use ($name, $price) {
                    return new Product($result->insertId, $name, $price);
                }
            );
    }

    public function getById(int $id): PromiseInterface
    {
        return $this->connection
            ->query('SELECT id, name, price FROM products WHERE id = ?', [$id])
            ->then(
                function (QueryResult $result) {
                    if (empty($result->resultRows)) {
                        throw new ProductNotFound();
                    }
                    $row = $result->resultRows[0];
                    return new Product(
                        (int)$row['id'],
                        $row['name'],
                        (float)$row['price']
                    );
                }
            );
    }
}
