<?php

require_once("./modules/Event/EventSource.php");
require_once("./modules/Event/EventObserverInterface.php");

class ServerRequestEvent extends EventSource implements EventObserverInterface {

	private $eventQueue;

	private $state;

	public function getState() {
		return $this->state;
	}

	public function setState($state) {
		$this->state = $state;
	}

	/* override */
	public function update() {

	}
}

?>
