<?php

require_once("./modules/Socket/SocketServer.php");

class ResourceMonitor {

	private $keyBoxMonitor;

	public function __construct() {
		$this->keyBoxMonitor = new SocketServer(Socket::UNIX, "/tmp/keybox.sock");
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
				// TODO 
				break;
			}
			//TODO select
			
			$e = null;

			if(socket_select($r, $w, $e, NULL) === false) {
				echo __FUNCTION__.":socket select error. ".
					socket_strerror(socket_last_error()).PHP_EOL;
			}

			$res = socket_read($connection, 2048);
			if($res === false) {
				socket_close($connection);
				break;
			}
			echo "socket msg:".$res.PHP_EOL;
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
