<?php


require __DIR__ . '/vendor/autoload.php';


use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use \React\Socket\Server as Socket;
use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;


$loop = Factory::create();

$dispatcher = simpleDispatcher(
    function (RouteCollector $routes) {
        $routes->get(
            '/products', function (ServerRequestInterface $request) {
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'GET request to /products'])
            );
        });
        $routes->post(
            '/products', function (ServerRequestInterface $request) {
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'POST request to /products'])
            );
        });
    }
);
$server = new Server(
    $loop,
    function (ServerRequestInterface $request) use ($dispatcher) {
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );
        $result = $routeInfo[0];

        switch ($result) {
            case Dispatcher::NOT_FOUND:
                return new Response(
                    404,
                    ['Content-Type' => 'text/plain'],
                    'Not found'
                );

            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(
                    405,
                    ['Content-Type' => 'text/plain'],
                    'Method not allowed'
                );

            case Dispatcher::FOUND:
                return $routeInfo[1]($request);
        }
        throw new LogicException('Something went wrong');
    }
);

$socket = new Socket('0.0.0.0:8000', $loop);
$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp', 'http', $socket->getAddress())
    . PHP_EOL;

$loop->run();
