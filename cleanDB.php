<?php include "config.php" ?>

<?php

	$verbose=$config["verbose"];

#$filename = 'cl.php';
#$s = 'DO NOT REMOVE THIS FILE! Unless you want to clean your DB!';

#if (file_exists($filename)){
#	echo "Db is already cleaned!";
#} else {
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
	
	#file_put_contents($filename, $s);
	
#}


?>
 
