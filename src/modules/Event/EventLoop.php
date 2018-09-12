<?php

require_once("./modules/Event/EventSource.php");
require_once("./modules/Socket/SocketServer.php");

class EventLoop {

	private $eventSourceArray;

	private $context;

	/* receive the event */
	private $socketServer;

	private $clients;

	public function __construct($context) {
		$this->eventSourceArray = array();

		$this->context = $context;
		$this->clients = array();
		$this->socketServer = new SocketServer(Socket::UNIX, "/tmp/event.sock");
	}

	private function startReceiveEvent() {

		if(!$this->socketServer->listen()) {
			echo __FUNCTION__.":listen faild.".PHP_EOL;
		}

        $pid = pcntl_fork();
        if($pid == -1) {
            echo "fork error.".PHP_EOL;
        }
        // parent
        else if($pid) {
            return;
        }
        if($pid != 0)
            return;

		echo "waiting event client...:".getmypid().PHP_EOL;
		for(;;) {
			echo "waiting connection...".PHP_EOL;
			$connection = $this->socketServer->accept_connection();

			if($connection === false) {
				// TODO  error handling
				echo "socket may has been closeed.".PHP_EOL;
				break;
			}
			echo __FUNCTION__.":New Connection:".PHP_EOL;
			/* add new client to clients */
			$this->clients[] = $connection;

			/* read the resource information */
			$this->connectionRoutine(
				$this->socketServer->getSocket(), $connection);
		}
		exit();
	}

	private function connectionRoutine($socket, $connection) {
		/* event loop for each client */
		$loop = function($args) {
			echo __FUNCTION__.":handle event Connection:".getmypid().PHP_EOL;
			for(;;) {
				$readfds[0] = $this->socketServer->getSocket();
				$readfds[1] = $args[0];
				$writefds = null;
				$e = null;

				$result = socket_select($readfds, $writefds, $e, NULL);
				if($result === false) {
					echo __FUNCTION__.":socket select error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}

				/* retry */
				if(!in_array($args[0], $readfds))
					continue;

				if($result == 0)
					echo "no available resource to read". PHP_EOL;

				$res = socket_read($args[0], 2048);
				if($res === false) {
					echo __FUNCTION__.":socket read error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}
				if(empty($res)) {
					echo __FUNCTION__.":EventLoop:".getmypid().":EOF".PHP_EOL;
					break;
				}

				$this->parseAndNotify($res);
			}
			echo __FUNCTION__.":end of event Connection:".getmypid().PHP_EOL;
			socket_close($args[0]);
		};

		Thread::run($loop, array($connection));
	}

	private function parseAndNotify($res) {
		echo __FUNCTION__.":".$res.PHP_EOL;
	}

	public function addEventSource($src) {
		if($src instanceof EventSource) {
			$this->eventSourceArray[get_class($src)] = $src;
		}
	}

	public function registerEvent($eventType, $observer) {
	}

	public function notifyObserver($observerType) {
	}

	public function notifyAllObserver() {
	}

	public function start() {
		echo __FUNCTION__.PHP_EOL;
		$this->startReceiveEvent();
	}

	public function stop() {
		foreach($this->clients as $client) {
			socket_close($client);
		}

		$this->socketServer->close();
	}
}

?>
