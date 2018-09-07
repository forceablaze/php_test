<?php

require_once("./Context.php");
require_once("./Event/EventLoop.php");

//TODO register interrupt handler

$context = new Context();
$main = new EventLoop();

//TODO serialize context

?>
