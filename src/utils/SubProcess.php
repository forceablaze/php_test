<?php

class SubProcess {

	public static function execAsync($program, $args, &$res, $handlers, $env) {
		echo __FUNCTION__.PHP_EOL;

		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
		);

        if(!empty($args)) {
		    foreach($args as $arg) {
			    $program .= ' ';
			    $program .= $arg;
		    }
        }

        $pipes;
        // do fork
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

        echo "my pid:".getmypid().PHP_EOL;
		$process = proc_open($program, $descriptorspec,
			$pipes, NULL, NULL);

		if(is_resource($process)) {

			$res = array(
				0 => $process,
				1 => $pipes,
			);
            //stream_set_blocking($pipes[0], false);
            //stream_set_blocking($pipes[1], false);
            //stream_set_blocking($pipes[2], false);
			//fclose($pipes[0]);
			//echo stream_get_contents($pipes[1]);
			//fclose($pipes[1]);
			//echo stream_get_contents($pipes[2]);
			//fclose($pipes[2]);
			if(!empty($handlers[0]))
				$handlers[0]($pipes);

			fclose($pipes[0]);
			stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			stream_get_contents($pipes[2]);
			fclose($pipes[2]);

			$ret = proc_close($process);
			if(!empty($handlers[1]))
				$handlers[1]($ret);

            exit();
			return;
		}
        $res = null;
		$handlers[0](null);
        exit();
	}

    public static function waitAllSubProcess() {
        while(pcntl_waitpid(0, $status, WUNTRACED) != -1) {
            if(pcntl_wifstopped($status)) {
                echo "SubProcess stopped return code:".pcntl_wexitstatus($status).PHP_EOL;
            }
            if(pcntl_wifexited($status)) {
                echo "SubProcess return code:".pcntl_wexitstatus($status).PHP_EOL;
            }
        }
    }
}

?>
