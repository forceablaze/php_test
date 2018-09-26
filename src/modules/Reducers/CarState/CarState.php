<?php

require_once("./modules/Redux/State.php");

class CarState extends State {

	/* initial state */
	const STATUS_EMPTY = 10; 
	const STATUS_USING = 30;

	public function __construct($state) {
		parent::__construct($state);
	}
}

?>
