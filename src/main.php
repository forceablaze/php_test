<?php
declare(ticks = 1);

require_once("./Context.php");
require_once("./modules/Event/EventLoop.php");
require_once("./modules/Event/DummyEvent/DummyEvent.php");

require_once("./utils/SubProcess.php");

$context = new Context();
$main = new EventLoop($context);
$dummyEvents = array();

function stopMainLoop() {
	global $main;
	global $dummyEvents;

	foreach($dummyEvents as $ev) {
		$ev->stopFetch();
	}
	$main->stop();
}

function shutdown() {
	stopMainLoop();
//TODO serialize context
}
/* when child exit() also execute the shutdown function */
//register_shutdown_function("shutdown");

function sig_handler($signo) {
	echo "receive signal:".$signo.PHP_EOL;
	switch($signo) {
		case SIGINT:
		case SIGTERM:
			stopMainLoop();
			break;
		default:
			echo "ignore signal".PHP_EOL;
	}
}

echo "pid:".getmypid().PHP_EOL;

//$serverRequestEvent = new ServerRequestEvent();

$main->start();
/* do register signal after fork process */
echo "Installing signal handler..".PHP_EOL;
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGINT, "sig_handler");


for($i = 0; $i < 500; $i++) {
	$dummyEvents[$i] = new DummyEvent();
	$dummyEvents[$i]->setEventId($i);
	$dummyEvents[$i]->fetchEvent();
}

//$main->addEventSource($dummyEvent);
//$main->addEventSource($dummyEvent2);

SubProcess::waitAllSubProcess();
?>
