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

	public function getWritableStream() {
	}

	public function getReadableStream() {
	}
}

?>
