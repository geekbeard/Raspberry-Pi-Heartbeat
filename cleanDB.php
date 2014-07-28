<?php require_once "config.php" ?>
<?php require_once "db.php" ?>
<?php require_once "funs.php" ?>

<?php

	$verbose=$config["verbose"];

	$filename = "db.lck";
	$s = 'DO NOT REMOVE THIS FILE! Unless you want to clean your DB!';

	if (file_exists($filename)){
		if($verbose) {
			echo "DB cleaning is locked. Delete $filename to clean database!";
		}
	} 
	else {
		//DROP TABLE
		//y: for some reason - this stoped working on my rpi...doesn't drop the table... will check what's that.
		DB::exec("DROP TABLE IF EXISTS rpi_temp");
	
		if($verbose)
			echo "DB Droped! <br>";
		DB::exec("CREATE TABLE 'rpi_temp' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'temp' REAL, 'time' TEXT, 'cpu' REAL,'rtemp' REAL,'hum' REAL)");
	
		if($verbose)
			echo "DB created! <br>";
		
		Stats::measureTemp();
	
		if($verbose)
			echo "First values inserted! Good luck:) <br>";
		
		file_put_contents($filename, $s);
	
	}
?>
 
