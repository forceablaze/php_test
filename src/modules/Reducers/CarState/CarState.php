<?php

require_once("./modules/Redux/State.php");

class CarState extends State {

	/* initial state */
	const STATUS_EMPTY = 10; 

	public function __construct($state) {
		parent::__construct($state);
	}
}

?>
