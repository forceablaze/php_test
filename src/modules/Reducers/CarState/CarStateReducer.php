<?php

require_once("./modules/Redux/Reducer.php");
require_once("./modules/Reducers/CarState/CarState.php");
require_once("./modules/Reducers/CarState/CarStateActions.php");

class CarStateReducer implements Reducer {

	/* override */
	public function reduce($previousState, $action) {
		if(!isset($previousState))
			return CarState::STATUS_EMPTY;

		echo "previouseState:".$previousState.PHP_EOL;

		switch($action->getType()) {
			case CarStateActions::ACTION_INIT:
				return CarState::STATUS_USING;
			case CarStateActions::DUMMY_ACTION:
				return CarState::STATUS_EMPTY;
			default:
				throw new Exception("No action defined.");
		}

		return $previousState;
	}
}

?>
