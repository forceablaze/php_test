<?php

class Socket {

	protected $address;

	protected $port;

	protected $type;

	protected $socket;

	const TCP = 0;
	const UNIX = 1;

	public function __construct($type = Socket::CONNECTION_TYPE_TCP,
		$address, $port = 0) {
		$this->socket = null;
		$this->address = $address;
		$this->port = $port;
		$this->type = $type;
	}

	public function close() {
		if($this->socket) {
			@socket_shutdown($this->socket);
			if(is_resource($this->socket))
				socket_close($this->socket);
		}
	}

	public function getSocket() {
		return $this->socket;
	}

	public function getAddress() {
		return $this->address;
	}

	public function write($buf) {

		$retry = 3;

		while($retry) {
			$nums = socket_write($this->socket, $buf, strlen($buf));
			if($nums === false) {
				echo static::class.":".__FUNCTION__.":".__FILE__.":".__LINE__."socket write error.".
					socket_strerror(socket_last_error()).PHP_EOL;
				$retry--;
				continue;
			}
			break;
		}

		if($retry == 0)
			return false;
		return $nums;
	}

	public function getWritableStream() {
	}

	public function getReadableStream() {
	}
}

?>
