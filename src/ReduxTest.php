<?php

require_once("./modules/Redux/ReduxStore.php");
require_once("./modules/Redux/StoreCreator.php");
require_once("./modules/Reducers/CarState/CarStateActions.php");
require_once("./modules/Reducers/CarState/CarStateReducer.php");

$reducer = new CarStateReducer();
$store = StoreCreator::createStore($reducer);

print_r($store->getState());

echo CarStateActions::dummyAction()->toString();

?>
