<?php

require_once("./modules/Socket/Socket.php");

class SocketClient extends Socket {

	private $isConnected;

	public function __construct($type = Socket::TCP,
		$address, $port = 0) {
		parent::__construct($type, $address, $port);
		$this->isConnected = false;
	}

	private function connectTCP() {
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		if($this->socket === false) {
			echo __FUNCTION__.":socket create failed. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			return false;
		}

		$ret = socket_connect($this->socket, $this->address, $this->port);

		if($ret === false) {
			echo __FUNCTION__.":socket connect failed. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			$this->close();
			return false;
		}
	}

	private function connectUNIX() {
		$this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

		if($this->socket === false) {
			echo __FUNCTION__.":socket create failed. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			return false;
		}

		$ret = socket_connect($this->socket, $this->address);

		if($ret === false) {
			echo __FUNCTION__.":socket connect failed. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			$this->close();
			return false;
		}
		return $ret;
	}

	public function connect() {
		$ret = false;
		switch($this->type) {
		case self::TCP:
				$ret = $this->connectTCP();
				break;
		case self::UNIX:
				$ret = $this->connectUNIX();
				break;
		default:
				$ret = false;
		}

		$this->isConnected = $ret;
	}

	public function close() {
		parent::close();
		$this->isConnected = false;
	}
}

?>
