<?php


require __DIR__ . '/vendor/autoload.php';


use React\EventLoop\Factory;
use React\Http\Server;
use \React\Socket\Server as Socket;
use FastRoute\RouteCollector;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;


use App\Core\Router;
use App\Core\ErrorHandler;
use App\Products\Controller\GetAllProducts;
use App\Products\Controller\CreateProduct;
use App\Products\Controller\GetProductById;
use App\Products\Controller\DeleteProduct;
use App\Products\Controller\UpdateProduct;


$loop = Factory::create();

$routes = new RouteCollector(new Std(), new GroupCountBased());
$routes->get('/products', new GetAllProducts());
$routes->get('/products/{id:\d+}', new GetProductById());
$routes->post('/products', new CreateProduct());
$routes->delete('/products/{id:\d+}', new DeleteProduct());
$routes->put('/products/{id:\d+}', new UpdateProduct());

$server = new Server(
    $loop,
    new ErrorHandler(),
    new Router($routes)
);

$socket = new Socket('0.0.0.0:8000', $loop);
$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp', 'http', $socket->getAddress())
    . PHP_EOL;

$loop->run();
