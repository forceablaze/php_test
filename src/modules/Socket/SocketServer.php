<?php

require_once("./modules/Socket/Socket.php");

class SocketServer extends Socket {

	private $connection;

	public function __construct($type = Socket::TCP,
		$address, $port = 0) {
		parent::__construct($type, $address, $port);
	}

	private function initialize() {
		$this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

		// check address is in use or not
		if(file_exists($this->address)) {
			unlink($this->address);
		}

		$ret = socket_bind($this->socket, $this->address);

		if($ret === false) {
			echo __FUNCTION__.":bind failed. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			return false;
		}
		return true;
	}

	public function listen() {

		$ret = $this->initialize();
		if(!$ret) {
			$this->close();
			return false;
		}

		$ret = socket_listen($this->socket);

		if($ret === false) {
			echo __FUNCTION__.":listen error. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			return false;
		}
		return true;
	}

	public function accept_connection() {
		$this->connection = @socket_accept($this->socket);

		if($this->connection === false) {
			echo __FUNCTION__.":accept connection error. ".
				socket_strerror(socket_last_error()).PHP_EOL;
			return false;
		}

		return $this->connection;
	}
}

?>
