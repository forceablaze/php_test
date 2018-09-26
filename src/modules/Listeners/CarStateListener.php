<?php

require_once("./modules/Redux/Listener.php");

class CarStateListener implements Listener {

	/* override */
	public function onDispatch() {
		echo "CarState:".__FUNCTION__.PHP_EOL;
	}
}
?>
