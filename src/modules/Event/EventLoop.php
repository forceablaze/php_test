<?php

require_once("./modules/Event/EventSource.php");

class EventLoop {

	private $eventSourceArray;

	public function __construct() {
		$this->eventSourceArray = array();
	}

	public function addEventSource($src) {
		if($src instanceof EventSource) {
			$this->eventSourceArray[get_class($src)] = $src;
		}
		print_r($this->eventSourceArray);
	}

	public function registerEvent($eventType, $observer) {
	}

	public function notifyObserver($observerType) {
	}

	public function notifyAllObserver() {
	}

	public function startLoop() {
	}
}

?>
