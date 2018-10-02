<?php

require_once("./modules/Event/BaseEvent/BaseEvent.php");

require_once("./modules/Socket/Socket.php");
require_once("./modules/Socket/SocketClient.php");

require_once("./utils/Thread.php");

class ServerRequestEvent extends BaseEvent {

	private $client = null;

	private $eventId;

	public function __construct($socket) {
		if(!$socket instanceof Socket) {
			throw new Exception("Argument must be a Socket.");
		}

		parent::__construct();
		$this->client = $socket;
	}

	private function readAndParseEvent($connection) {

		$this->client->write("AHAHAHA");

		for(;;) {
			$res = socket_read($connection, 2048);
			if($res === false) {
				echo __FUNCTION__.":socket read error. ".
					socket_strerror(socket_last_error()).PHP_EOL;
				break;
			}

			if(empty($res))
				break;

			$this->eventId = $res;
			echo __FUNCTION__.":".$res.PHP_EOL;
		}
		echo __FUNCTION__."end".PHP_EOL;
	}

	public function fetchEvent() {

		if(empty($this->client))
			return false;

		$connectRoutine = function() {
			for(;;) {
				//echo "trying connect to server....".PHP_EOL;

				if(!$this->client->connect()) {
					echo "connect to:".$this->client->getAddress().
						" failed.".PHP_EOL;
					usleep(30000);
					continue;
				}

				$connection = $this->client->getSocket();

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
				$this->client->close();
			}
		};

		//Thread::run($connectRoutine);
		parent::fetchEvent();
	}

	/* override */
	public function getEvent(&$event) {
		echo $this->eventId.PHP_EOL;
		sleep(1);
		$event = $this->eventId;
	}
}

?>
