<?php

require_once("./modules/Event/EventLoop.php");
require_once("./modules/Event/ServerEvent/ServerRequestEvent.php");

require_once("./modules/Socket/SocketClient.php");
require_once("./modules/Socket/SocketServer.php");

require_once("./Context.php");
require_once("./modules/Status/CarStatus.php");
require_once("./modules/Resource/ResourceMonitor.php");

require_once("./utils/Thread.php");

$echoTest = function() {
	for(;;) {
		echo "test".PHP_EOL;
		sleep(1);
	}
};
//Thread::run($echoTest);

$serverEvent = new ServerRequestEvent();
$serverEvent->setState(1);
echo $serverEvent->getState().PHP_EOL;
$serverEvent->fetchEvent();

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
$monitor->stopMonitor();

$cli = new SocketClient(Socket::UNIX, "/tmp/keybox.sock");
for($i = 0; $i < 5; $i++) {
	if($cli->connect()) {
		$res = $cli->getSocket();

		$readfds = null;
		$writefds = array($res);
		$e = null;

		if(socket_select($readfds, $writefds, $e, NULL) === false) {
			echo __FUNCTION__.":socket select error. ".
			socket_strerror(socket_last_error()).PHP_EOL;
		}
		socket_write($res, "Test");
	}
	$cli->close();
}

$context = Context::getInstance();
echo $context->getStatus(CarStatus::class).PHP_EOL;
?>
