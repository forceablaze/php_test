<?php

require_once("./modules/Event/EventSource.php");
require_once("./modules/Event/EventObserverInterface.php");
require_once("./modules/Event/BaseEvent/BaseEvent.php");

require_once("./utils/Thread.php");

class DummyEvent extends BaseEvent {

	private $eventId;

	public function __construct() {
		parent::__construct();
	}

	public function setEventId($id) {
		$this->eventId = $id;
	}

	/* override */
	public function getEvent(&$event) {
		sleep(1);
		$event = $this->eventId;
	}
}

?>
