<?php

require_once("./modules/Status/CarStatus.php");

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
	public function initializeStatus() {
		$this->statusMap[CarStatus::class] = CarStatus::STATUS_EMPTY;
	}

	/* initialize resource */
	public function initializeResource() {
		// KeyBox
		// GPS
		// CardReader
	}

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
