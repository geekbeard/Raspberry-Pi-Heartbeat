<?php
	

	$config["srv_dir"] = "" ;
	$config["root_dir"] = "/var/www/" ; #chang the path (/var/www/temp/) to where you unziped the package
	$root_dir = $config["root_dir"] ;
	
	
	
	$config["temperatureURL"] = "{$config["srv_dir"]}temp.php" ;
	$config["cpuURL"] = "{$config["srv_dir"]}cpu.php" ;
	$config["temperature_DB"] = "$root_dir/rpiTemp.db" ; #DB name. don't change unless you know what you are doing:)
	$config["verbose"] = false ; #whether you want debug information (cleaned DB etc)

	#print_r($config);
?>
