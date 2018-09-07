<?php

require_once("./modules/Status/Status.php");

class CarStatus extends Status {

	const STATUS_EMPTY = 10; 
	const STATUS_CHECKING = 20; 
	const STATUS_USING = 30; 
	const STATUS_WAITING_END = 31; 
	const STATUS_WAITING_BEGIN = 32; 
	const STATUS_WAITING_LOCK = 40; 
	const STATUS_WAITING_UNLOCK = 50; 
}

?>
