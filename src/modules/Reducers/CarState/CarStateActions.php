<?php

require_once("./modules/Redux/Action.php");

class CarStateActions {

	const DUMMY_ACTION = "DUMMY";
	const ACTION_INIT = "INIT";

	public static function dummyAction() {
		return Action::of(CarStateActions::DUMMY_ACTION);
	}

	public static function initAction() {
		return Action::of(CarStateActions::ACTION_INIT);
	}
}

?>
