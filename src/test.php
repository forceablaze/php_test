<?php
require_once("./modules/Event/EventLoop.php");
require_once("./modules/Event/ServerEvent/ServerRequestEvent.php");

require_once("./modules/Socket/SocketClient.php");
require_once("./modules/Socket/SocketServer.php");

require_once("./Context.php");
require_once("./modules/Status/CarStatus.php");
require_once("./modules/Resource/ResourceMonitor.php");

$serverEvent = new ServerRequestEvent();

$serverEvent->setState(1);
echo $serverEvent->getState();
$loop = new EventLoop();
$loop->addEventSource($serverEvent);
echo $serverEvent->getState() . PHP_EOL;

$socket = new SocketClient(Socket::TCP, "www.google.com.tw", 80);
$socket->connect();
echo "close".PHP_EOL;
$socket->close();

$monitor = new ResourceMonitor();
$monitor->startMonitor();

sleep(1);

$cli = new SocketClient(Socket::UNIX, "/tmp/keybox.sock");
if($cli->connect()) {
	$res = $cli->getSocket();
	socket_write($res, "Test");
}

$context = Context::getInstance();
echo $context->getStatus(CarStatus::class).PHP_EOL;

sleep(30);
$monitor->stopMonitor();
?>
