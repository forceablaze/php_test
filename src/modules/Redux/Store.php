<?php

interface Store {

	public function getState();

	/* return action */
	public function dispatch($action);

	public function subscribe($listener);

	public function replaceReducer($nextReducer);
}

?>
