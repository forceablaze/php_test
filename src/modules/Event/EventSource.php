<?php

class EventSource {

	private $eventQueue;

	private $socketClient = null;

	private $pipes;

	public function __construct() {
		$this->eventQueue = new SplQueue();
		$this->socketClient = new SocketClient(Socket::UNIX, "/tmp/event.sock");
	}

	public function enqueueEvent($event) {
		$this->eventQueue->enqueue($event);
	}

	protected function getWritablePipe() {
		return $this->pipes[0];
	}

	protected function getReadablePipe() {
		return $this->pipes[1];
	}

	public function startPushEvent() {
		$this->createPipeline();

		if(!$this->socketClient->connect()) {
			echo __FUNCTION__."connect to event socket failed";
			return false;
		}

		$connectRoutine = function() {
			echo __FUNCTION__.":send event Connection:".getmypid().PHP_EOL;
			socket_close($this->pipes[0]);

			for(;;) {
				$r = array($this->pipes[1]);
				$w = null;
				$e = null;
				if(socket_select($r, $w, $e, NULL) === false) {
					echo __FUNCTION__.":".getmypid().":socket read select error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}

				/* retry */
				if(!in_array($this->pipes[1], $r))
					continue;

				$event = @socket_read($this->pipes[1], 2048);
				if($event === false) {
					echo __FUNCTION__.":socket read error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					continue;
				}
				if(empty($event)) {
					echo __FUNCTION__.":EventSource:".getmypid().":EOF".PHP_EOL;
					break;
				}
				echo __FUNCTION__.":event:".$event.PHP_EOL;

				$res = $this->socketClient->getSocket();
				$readfds = null;
				$writefds = array($res);
				$e = null;
				if(socket_select($readfds, $writefds, $e, NULL) === false) {
					echo __FUNCTION__.":".getmypid().":socket select error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}

				$nums = @socket_write($res, get_class($this)."::".$event."::");
				if($nums === false) {
					echo __FUNCTION__.":".getmypid().":socket write error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}
			}
			echo __FUNCTION__.":end of send event Connection:".getmypid().PHP_EOL;
		};

		Thread::run($connectRoutine);

		/* close read pipeline */
		socket_close($this->pipes[1]);
	}

	public function stopPushEvent() {
		socket_close($this->pipes[0]);
		unset($this->pipes);

		$this->socketClient->close();
	}

	public function getEvent(&$event) {
		$event = null;
	}

	public function notify() {
	}

	private function createPipeline() {
		/* using pipe to receive event from base class. */
		$this->pipes = array();
		if(socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->pipes) === false) {
			echo __FUNCTION__."create pipe failed.".PHP_EOL;
		}
	}
}

?>
