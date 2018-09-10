<?php

require_once("./modules/Socket/SocketServer.php");

class ResourceMonitor {

	private $keyBoxMonitor;

	public function __construct() {
		$this->keyBoxMonitor = new SocketServer(Socket::UNIX, "/tmp/keybox.sock");
	}

	private function connectionRoutine($socket, $connection) {

		$readfds[0] = $socket;
		$readfds[1] = $connection;
		$writefds = null;
		$e = null;

		$result = socket_select($readfds, $writefds, $e, NULL);
		if($result === false) {
			echo __FUNCTION__.":socket select error. ".
				socket_strerror(socket_last_error()).PHP_EOL;
		}
		if(!in_array($connection, $readfds))
			return;

		if($result == 0)
			echo "no available resource to read". PHP_EOL;

		for(;;) {
			$res = socket_read($connection, 2048);
			if($res === false) {
				echo __FUNCTION__.":socket read error. ".
					socket_strerror(socket_last_error()).PHP_EOL;
				break;
			}
			if(empty($res))
				break;
			echo "socket msg:".$res.PHP_EOL;
		}
	}

	private function __startMonitor($socketServer) {

		if(!$socketServer->listen()) {
			echo __FUNCTION__.":listen faild.".PHP_EOL;
		}

        $pid = pcntl_fork();
        if($pid == -1) {
            echo "fork error.".PHP_EOL;
        }
        // parent
        else if($pid) {
            return;
        }
        if($pid != 0)
            return;

		echo "start monitor".PHP_EOL;
		for(;;) {
			$connection = $socketServer->accept_connection();

			if($connection === false) {
				// TODO  error handling
				echo "socket may has been closeed.".PHP_EOL;
				break;
			}

			/* read the resource information */
			$this->connectionRoutine($socketServer->getSocket(), $connection);
			socket_close($connection);
		}
		exit();
	}

	public function startMonitor() {
		$this->__startMonitor($this->keyBoxMonitor);
	}

	public function stopMonitor() {
		$this->keyBoxMonitor->close();
	}
}

?>
