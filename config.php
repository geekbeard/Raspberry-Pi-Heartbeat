<?php
	
	
	$config["root_dir"] = "/var/www/temp/" ; #chang the path (/var/www/temp/) to where you unziped the package
	$root_dir = $config["root_dir"] ;
	
	
	
	$config["temperature_DB"] = "$root_dir/rpiTemp.db" ; #DB name. don't change unless you know what you are doing:)
	$config["verbose"] = true ; #whether you want debug information (cleaned DB etc)

	#print_r($config);
?>
