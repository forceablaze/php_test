<?php

require_once("./modules/Redux/ReduxStore.php");
require_once("./modules/Redux/combineReducers.php");
require_once("./modules/Redux/StoreCreator.php");
require_once("./modules/Reducers/CarState/CarStateActions.php");
require_once("./modules/Reducers/CarState/CarStateReducer.php");

$reducer = new CarStateReducer();

$reducers = combineReducers(array(
	"carState" => $reducer
));

$store = StoreCreator::createStore($reducers);

function listener() {
	echo __FUNCTION__.PHP_EOL;
}

$unsubscribe = $store->subscribe('listener');

$store->dispatch(CarStateActions::dummyAction());
$store->dispatch(CarStateActions::initAction());
$store->dispatch(CarStateActions::dummyAction());
$unsubscribe();

print_r($store->getState());

//echo CarStateActions::dummyAction()->toString();

?>
