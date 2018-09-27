<?php

require_once("./modules/Redux/Store.php");
require_once("./modules/Redux/Action.php");
require_once("./modules/Redux/ActionTypes.php");

class ReduxStore implements Store {

	private $currentState;

	private $currentReducer;

	private $isDispatching;

	private $currentListeners;

	private $nextListeners;

	public function __construct($reducer) {
		$this->currentReducer = $reducer;
		$this->currentListeners = array();
		$this->nextListeners = &$this->currentListeners;

		$this->isDispatching = false;

		$this->dispatch(Action::of(ActionTypes::INIT));
	}

	/* override */
	public function getState() {
		return $this->currentState;
	}

	/* override */
	public function dispatch($action) {

		if(!$action instanceof Action) {
			throw new Exception("Expected the observer to be an Action.");
		}

		if($this->isDispatching) {
			return null;
		}

		$reducer = $this->currentReducer;
		try {
			$this->isDispatching = true;
			$this->currentState =
				$reducer($this->currentState, $action);
		} finally {
			$this->isDispatching = false;
		}

		$this->currentListeners = &$this->nextListeners;
		$listeners = &$this->currentListeners;

		foreach($listeners as $listener) {
			$listener();
		}

		return $action;
	}

	/* override */
	public function subscribe($listener) {
		if(!is_callable($listener)) {
			throw new Exception('Expected listener to be a function.');
		}

		$isSubscribe = true;
		$this->ensureCanMutateNextListeners();
		array_push($this->nextListeners, $listener);

		/* unsubscribe function */
		return function() use($listener, $isSubscribe) {
			if(!$isSubscribe) {
				return;
			}

			$isSubscribe = false;

			$this->ensureCanMutateNextListeners();
			$index = array_search($listener, $this->nextListeners);
			array_splice($this->nextListeners, $index, 1);
		};
	}

	/* override */
	public function replaceReducer($nextReducer) {
	}

	/* The goal here is to ensure that the listeners
     * that are used by dispatch are a point in time, for when the dispatch started.
	 */
	private function ensureCanMutateNextListeners() {
		$this->nextListeners = $this->currentListeners;
	}
}

?>
