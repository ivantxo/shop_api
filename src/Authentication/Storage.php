<?php


namespace App\Authentication;


use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use function React\Promise\reject;


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

    public function create(string $email, string $password): PromiseInterface
    {
        return $this->connection->query(
            'INSERT INTO users (email, password) VALUES (?, ?)',
            [$email, $password]
        );
    }

    private function emailIsNotTaken(string $email): PromiseInterface
    {
        return $this->connection
            ->query('SELECT 1 FROM users WHERE email = ?', [$email])
            ->then(
                function (QueryResult $result) {
                    return empty($result->resultRows)
                        ? resolve()
                        : reject(new UserAlreadyExists());
                }
            );
    }
}
