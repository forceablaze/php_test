<?php

require_once("./modules/Status/CarStatus.php");
require_once("./modules/Event/ServerEvent/ServerRequestEvent.php");

require_once("./modules/Event/EventLoop.php");

class Context {

	public static $instance = null;

	private $statusMap;

	private $resourceMon;

	private $eventLoop;

	public function __construct() {
		$this->statusMap = array();

		$this->initializeStatus();
		$this->initializeResource();
	}

	/* initialize each status */
	private function initializeStatus() {
		$this->statusMap[CarStatus::class] = CarStatus::STATUS_EMPTY;
	}

	/* initialize resource */
	private function initializeResource() {
		// KeyBox
		// GPS
		// CardReader
	}

	/*
	private function initializeEventSource() {
		$this->eventLoop = new EventLoop();

		$serverRequestEvent = new ServerRequestEvent();

		$this->eventLoop->addEventSource($serverRequestEvent);
	}
	*/

	public function getStatus($type) {
		return $this->statusMap[$type];
	}

	public static function getInstance() {
		if(self::$instance) 
			return self::$instance;
		else
			return new Context();
	}
}

?>
