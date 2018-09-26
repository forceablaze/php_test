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
print_r($store->getState());
$store->dispatch(CarStateActions::initAction());
print_r($store->getState());
$store->dispatch(CarStateActions::dummyAction());
print_r($store->getState());
$unsubscribe();


//echo CarStateActions::dummyAction()->toString();

?>
