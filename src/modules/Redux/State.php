<?php

class State {

	private $state;

	public function __construct($state) {
		$this->state = $state;
	}

	public function getState() {
		return $this->state;
	}
}

?>
