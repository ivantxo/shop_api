<?php


namespace App\Orders\Controller\Output;


final class Request
{
    private const URI = 'http://localhost:8000/orders/';

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $url
     */
    public $url;

    /**
     * @var array|null $body
     */
    public $body;

    public function __construct(string $type, string $url, array $body = null)
    {
        $this->type = $type;
        $this->url = $url;
        $this->body = $body;
    }

    public static function detailedOrder(int $id): self
    {
        return new self('GET', self::URI . $id);
    }

    public static function listOrders(): self
    {
        return new self('GET', self::URI);
    }
}
