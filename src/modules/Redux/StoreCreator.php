<?php


require_once("./modules/Redux/ReduxStore.php");

class StoreCreator {

	public static function createStore($reducer) {

		$store = new ReduxStore($reducer);

		return $store;
	}
}

?>
