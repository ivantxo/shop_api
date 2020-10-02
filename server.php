<?php


require __DIR__ . '/vendor/autoload.php';


use React\EventLoop\Factory;
use React\Http\Server;
use \React\Socket\Server as Socket;
use FastRoute\RouteCollector;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use React\MySQL\Factory as MySQLFactory;
use Dotenv\Dotenv;


use App\Core\Router;
use App\Core\ErrorHandler;
use App\Core\JsonRequestDecoder;
use App\Products\Storage as Products;
use App\Products\Controller\GetAllProducts;
use App\Products\Controller\CreateProduct;
use App\Products\Controller\GetProductById;
use App\Products\Controller\DeleteProduct;
use App\Products\Controller\UpdateProduct;
use App\Orders\Storage as Orders;
use App\Orders\Controller\GetAllOrders;
use App\Orders\Controller\CreateOrder;
use App\Orders\Controller\GetOrderById;
use App\Authentication\SignUpController;
use App\Authentication\Storage as Users;
use App\Authentication\Authenticator;
use App\Authentication\SignInController;
use App\Authentication\Guard;

$loop = Factory::create();

$env = Dotenv::createImmutable(__DIR__);
$env->load();
$mysql = new MySQLFactory($loop);
$uri = $_ENV['DB_USER']
    . ':' . $_ENV['DB_PASS']
    . '@' . $_ENV['DB_HOST']
    . '/' . $_ENV['DB_NAME'];
$connection = $mysql->createLazyConnection($uri);
$products = new Products($connection);
$orders = new Orders($connection);
$users = new Users($connection);
$authenticator = new Authenticator($users, $_ENV['JWT_KEY']);
$guard = new Guard($_ENV['JWT_KEY']);

$routes = new RouteCollector(new Std(), new GroupCountBased());

// products routes
$routes->get('/products', new GetAllProducts($products));
$routes->get('/products/{id:\d+}', new GetProductById($products));
$routes->post('/products', $guard->protect(new CreateProduct($products)));
$routes->delete('/products/{id:\d+}', new DeleteProduct($products));
$routes->put('/products/{id:\d+}', new UpdateProduct($products));

// orders routes
$routes->get('/orders', new GetAllOrders($orders));
$routes->post('/orders', new CreateOrder($orders, $products));
$routes->get('/orders/{id:\d+}', new GetOrderById($orders));

$routes->post('/auth/signup', new SignUpController($users));
$routes->post('/auth/signin', new SignInController($authenticator));

$server = new Server(
    $loop,
    new ErrorHandler(),
    new JsonRequestDecoder(),
    new Router($routes)
);

$socket = new Socket('0.0.0.0:8000', $loop);
$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp', 'http', $socket->getAddress())
    . PHP_EOL;

$loop->run();
