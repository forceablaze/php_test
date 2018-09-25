<?php

require_once("./modules/Redux/Store.php");
require_once("./modules/Redux/Action.php");
require_once("./modules/Redux/ActionTypes.php");

class ReduxStore implements Store {

	private $currentState;

	private $currentReducer;

	private $isDispatching;

	/* TODO add listener */

	public function __construct($reducer) {
		$this->currentReducer = $reducer;
		$this->isDispatching = false;

		var_dump($this->isDispatching);
		$this->dispatch(Action::of(ActionTypes::INIT));
	}

	/* override */
	public function getState() {
		return $this->currentState;
	}

	/* override */
	public function dispatch($action) {

		if($this->isDispatching) {
			return null;
		}

		try {
			$this->isDispatching = true;
			$this->currentState =
				$this->currentReducer->reduce($this->currentState, $action);
		} finally {
			$this->isDispatching = false;
		}

		return $action;
	}

	/* override */
	public function subscribe($listener) {
	}

	/* override */
	public function replaceReducer($nextReducer) {
	}
}

?>
