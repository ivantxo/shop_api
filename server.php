<?php


require __DIR__ . '/vendor/autoload.php';


use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use \React\Socket\Server as Socket;


$loop = Factory::create();
$server = new Server($loop, function () {});

$socket = new Socket('0.0.0.0:8000', $loop);
$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp', 'http', $socket->getAddress())
    . PHP_EOL;

$loop->run();
