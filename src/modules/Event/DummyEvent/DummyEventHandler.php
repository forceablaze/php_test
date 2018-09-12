<?php

require_once("./modules/Event/EventObserverInterface.php");
require_once("./modules/Event/Handler/HandlerInterface.php");

class DummyEventHandler implements HandlerInterface, EventObserverInterface {

	/* implement */
	public function update() {

	}

	/* implement */
	public function handler() {
	}
}

?>
