
<?php
	
	//Class that handles interaction with our database
	class DB {

		private static $db = null ;

		private static function init() {
			if(DB::$db==null) {
				include "config.php" ;
				DB::$db = new SQLite3($config["temperature_DB"]);
			}
				
		}

	    public static function query($query) {
	    	DB::init();
	    	return DB::$db->query($query);
	    }
	    public static function exec($query) {
	    	DB::init();
	    	return DB::$db->exec($query);
	    }
	}
	
?>