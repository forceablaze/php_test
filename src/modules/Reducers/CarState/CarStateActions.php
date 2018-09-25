<?php

require_once("./modules/Redux/Action.php");

class CarStateActions {

	const DUMMY_ACTION = "DUMMY";

	public static function dummyAction() {
		return Action::of(CarStateActions::DUMMY_ACTION);
	}
}

?>
