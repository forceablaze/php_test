<?php

require_once("./modules/Redux/State.php");

interface Reducer {

	/* return State */
	public function reduce($previousState, $action);
}

?>
