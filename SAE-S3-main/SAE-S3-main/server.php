<?php
require __DIR__ . '/vendor/autoload.php';

use App\SAE3\Lib\Websocket;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Websocket()
        )
    ),
    8080
);

$server->run();