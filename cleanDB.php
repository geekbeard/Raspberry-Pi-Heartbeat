<?php include "config.php" ?>

<?php

	$verbose=$config["verbose"];

	$filename = 'db.lck';
	$s = 'DO NOT REMOVE THIS FILE! Unless you want to clean your DB!';

	if (file_exists($filename)){
		if($verbose) {
			echo "DB cleaning is locked. Delete $filename to clean database!";
		}
	} 
	else {
		$db = new SQLite3($config["temperature_DB"]);
		//DROP TABLE
		$db->query('DROP TABLE rpi_temp');
	
		if($verbose)
			echo "DB Droped! <br>";
		$db->query("CREATE TABLE 'rpi_temp' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'temp' REAL, 'time' TEXT, 'cpu' REAL)");
	
		if($verbose)
			echo "DB created! <br>";
		$db = NULL;
		
		require 'measuretemp.php';
	
		if($verbose)
			echo "First values inserted! Good luck:) <br>";
		
		file_put_contents($filename, $s);
	
	}
?>
 
