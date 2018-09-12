<?php

class Thread {

	public static function run($callback, $args = null) {

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

		if(!empty($callback))
			$callback($args);
		exit();
	}
}

?>
