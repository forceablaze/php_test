<?php

require_once("./modules/Event/EventObserverInterface.php");
require_once("./modules/Event/Handler/HandlerInterface.php");

class ServerRequestHandler implements HandlerInterface, EventObserverInterface {

	/* implement */
	public function update($context) {

	}

	/* implement */
	public function handler() {
	}
}

?>
