<?php

require_once("./modules/Event/EventSource.php");
require_once("./modules/Event/EventObserverInterface.php");

require_once("./modules/Socket/Socket.php");
require_once("./modules/Socket/SocketClient.php");

require_once("./utils/Thread.php");

define("UPR_SERVER_SITE", "1.2.3.4");
define("ECHO_SERVER_SITE", "localhost");

class ServerRequestEvent extends EventSource implements EventObserverInterface {

	private $state;

	private $socketClient = null;

	public function __construct() {
		$this->socketClient =
			new SocketClient(Socket::TCP, ECHO_SERVER_SITE, 4242);
	}

	private function readAndParseEvent($connection) {
		echo __FUNCTION__.PHP_EOL;

		for(;;) {
			$res = socket_read($connection, 2048);
			if($res === false) {
				echo __FUNCTION__.":socket read error. ".
					socket_strerror(socket_last_error()).PHP_EOL;
				break;
			}

			if(empty($res))
				break;

			echo __FUNCTION__.":".$res.PHP_EOL;
			//$pipe = $this->getWritablePipe();
			//socket_write($pipe, $i);
		}
	}

	public function fetchEvent() {

		if(empty($this->socketClient))
			return false;

		$connectRoutine = function() {
			for(;;) {
				echo "trying connect to server....".PHP_EOL;

				if(!$this->socketClient->connect()) {
					echo "connect to:".$this->socketClient->getAddress().
						" failed.".PHP_EOL;
					continue;
				}

				$connection = $this->socketClient->getSocket();

				$readfds[0] = $connection;
				$writefds = null;
				$e = null;

				$numbers = socket_select($readfds, $writefds, $e, NULL);
				if($numbers === false) {
					echo __FUNCTION__.":socket select error. ".
					socket_strerror(socket_last_error()).PHP_EOL;
				}

				if(!in_array($connection, $readfds))
					echo __FUNCTION__."No available data".PHP_EOL;

				$this->readAndParseEvent($connection);
				$this->socketClient->close();
			}
		};

		Thread::run($connectRoutine);
	}

	public function getState() {
		return $this->state;
	}

	public function setState($state) {
		$this->state = $state;
	}

	/* override */
	public function update($context) {

	}
}

?>
