<?php

interface Store {

	public function getState();

	/* return action */
	public function dispatch($action);

	/* A function that unsubscribes the change listener. */
	public function subscribe($listener);

	public function replaceReducer($nextReducer);
}

?>
