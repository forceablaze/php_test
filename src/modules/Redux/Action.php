<?php

class Action {

	private $type;

	public function __construct($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public static function of($type) {
		return new Action($type);
	}

	public function toString() {
		return $this->type;
	}
}

?>
