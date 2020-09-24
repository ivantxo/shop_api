<?php


require __DIR__ . '/vendor/autoload.php';


use React\EventLoop\Factory;
use React\Http\Server;
use \React\Socket\Server as Socket;
use FastRoute\RouteCollector;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;


use App\Products\Controller\GetAllProducts;
use App\Products\Controller\CreateProduct;
use App\Router;


$loop = Factory::create();

$routes = new RouteCollector(new Std(), new GroupCountBased());
$routes->get('/products', new GetAllProducts());
$routes->post('/products', new CreateProduct());

$server = new Server(
    $loop,
    new Router($routes)
);

$socket = new Socket('0.0.0.0:8000', $loop);
$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp', 'http', $socket->getAddress())
    . PHP_EOL;

$loop->run();
