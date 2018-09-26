<?php

require_once("./modules/Event/EventSource.php");
require_once("./modules/Event/EventObserverInterface.php");

require_once("./utils/Thread.php");

class BaseEvent extends EventSource implements EventObserverInterface {

	private $internalPipes;

	public function __construct() {
		parent::__construct();
	}

	public function fetchEvent() {
		$this->createPipeline();
		$this->startPushEvent();

		$fetch = function() {
			$pipe = $this->getWritablePipe();
			socket_close($this->internalPipes[1]);
			for(;;) {

				$readfds = array($this->internalPipes[0]);
				$writefds = array($pipe);
				$e = null;
				if(socket_select($readfds, $writefds, $e, NULL) === false) {
					echo __FUNCTION__.":".getmypid().":socket select error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}

				if(in_array($this->internalPipes[0], $readfds)) {
					$nums = @socket_read($this->internalPipes[0], 1024);
					if($nums === false) {
						echo __FUNCTION__.":".getmypid().":socket write error. ".
							socket_strerror(socket_last_error()).PHP_EOL;
						break;
					}

					if(!empty($nums)) {
						if(strcmp($nums, "STOP") === 0)  {
							echo __FUNCTION__.":receive stop command".PHP_EOL;
							break;
						}
					}
				}

				/* retry */
				if(!in_array($pipe, $writefds))
					continue;

				$event = null;
				$this->getEvent($event);
				$nums = @socket_write($pipe, get_class($this)."::".$event."::");
				if($nums === false) {
					echo __FUNCTION__.":".getmypid().":socket write error. ".
						socket_strerror(socket_last_error()).PHP_EOL;
					break;
				}
			}
			echo "end of dummy event".PHP_EOL;
		};

		Thread::run($fetch);
		//socket_close($this->getWritablePipe());
		//socket_close($this->getReadablePipe());
		socket_close($this->internalPipes[0]);
	}

	private function createPipeline() {
		/* using pipe to receive event from base class. */
		$this->internalPipes = array();
		if(socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $this->internalPipes) === false) {
			echo __FUNCTION__."create pipe failed.".PHP_EOL;
		}
	}

	public function stopFetch() {
		$this->stopPushEvent();

		socket_write($this->internalPipes[1], "STOP");
		unset($this->internalPipes);
	}

	/* implement */
	public function update($context) {
	}
}

?>
