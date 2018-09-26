<?php

require_once("./modules/Redux/Reducer.php");

function combineReducers($reducers) {

	if(!is_array($reducers)) {
		throw new Exception("Invalid argument.");
	}

	$reducerKeys = array_keys($reducers);
	$finalReducers = array();

	foreach($reducerKeys as $key) {
		if($reducers[$key] instanceof Reducer) {
			$finalReducers[$key] = $reducers[$key];
		}
	}

	$finalReducerKeys = array_keys($finalReducers);

	return function($state, $action) use($finalReducerKeys, $finalReducers)  {

		$hasChanged = false;
		$nextState = array();

		foreach($finalReducerKeys as $key) {
			$reducer = $finalReducers[$key];
			$previousStateForKey = $state[$key];
			$nextStateForKey = $reducer->reduce($previousStateForKey, $action);

			$nextState[$key] = $nextStateForKey;

			$hasChanged = $hasChanged || $nextStateForKey !== $previousStateForKey;
		}

		return $hasChanged ? $nextState : $state;
	};
}

?>
