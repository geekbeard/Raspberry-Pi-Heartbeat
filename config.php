<?php

	$config["root_dir"] = "/mnt/usb/heartbeat/" ;
	$root_dir = $config["root_dir"] ;

	$config["temperature_DB"] = "$root_dir/rpiTemp.db" ;
	$config["verbose"] = false ; #whether you want debug information (cleaned DB etc)

	#print_r($config);
?>
