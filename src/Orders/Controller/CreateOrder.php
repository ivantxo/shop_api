<?php


namespace App\Orders\Controller;


use App\Core\JsonResponse;
use App\Products\ProductNotFound;
use Psr\Http\Message\ServerRequestInterface;
use App\Orders\Storage as Orders;
use App\Products\Storage as Products;
use App\Orders\Order;
use App\Orders\Controller\Output\Order as Output;
use App\Orders\Controller\Output\Request;
use App\Products\Product;


final class CreateOrder
{
    /**
     * @var Orders $orders
     */
    private $orders;

    /**
     * @var Products $products
     */
    private $products;

    public function __construct(Orders $orders, Products $products)
    {
        $this->orders = $orders;
        $this->products = $products;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();

        return $this->products
            ->getById($input->productId())
            ->then(
                function (Product $product) use ($input) {
                    return $this->orders
                        ->create($input->productId(), $input->quantity());
                }
            )
            ->then(
                function (Order $order) {
                    $response = [
                        'order' => Output::fromEntity(
                            $order,
                            Request::listOrders()
                        )
                    ];
                    return JsonResponse::created($response);
                }
            )
            ->otherwise(
                function (ProductNotFound $error) {
                    return JsonResponse::badRequest(
                        ['productId' => 'Product Not Found']
                    );
                }
            );
    }
}
