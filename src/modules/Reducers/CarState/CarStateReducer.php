<?php

require_once("./modules/Redux/Reducer.php");
require_once("./modules/Reducers/CarState/CarState.php");

class CarStateReducer implements Reducer {

	/* override */
	public function reduce($previousState, $action) {
		if(!isset($previousState))
			return CarState::STATUS_EMPTY;

		return $previousState;
	}
}

?>
