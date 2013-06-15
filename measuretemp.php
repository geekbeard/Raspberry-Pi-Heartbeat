<?php include "config.php" ?>

<?php 

	$db = new SQLite3($config["temperature_DB"]);
	
	$temp=round(exec("cat /sys/class/thermal/thermal_zone0/temp ") / 1000, 1);
	$dtime=date("Y-m-d H:i:s", strtotime ("+0 hour"));
	
	//CPU Usage
	$output1 = null;
	$output2 = null;
	//First sample
	exec("cat /proc/stat", $output1);
	//Sleep before second sample
	sleep(1);
	//Second sample
	exec("cat /proc/stat", $output2);
	$cpuload = 0;
	for ($i=0; $i < 1; $i++)
	{
		//First row
		$cpu_stat_1 = explode(" ", $output1[$i+1]);
		$cpu_stat_2 = explode(" ", $output2[$i+1]);
		//Init arrays
		$info1 = array("user"=>$cpu_stat_1[1], "nice"=>$cpu_stat_1[2], "system"=>$cpu_stat_1[3], "idle"=>$cpu_stat_1[4]);
		$info2 = array("user"=>$cpu_stat_2[1], "nice"=>$cpu_stat_2[2], "system"=>$cpu_stat_2[3], "idle"=>$cpu_stat_2[4]);
		$idlesum = $info2["idle"] - $info1["idle"] + $info2["system"] - $info1["system"];
		$sum1 = array_sum($info1);
		$sum2 = array_sum($info2);
		//Calculate the cpu usage as a percent
		$load = (1 - ($idlesum / ($sum2 - $sum1))) * 100;
		$cpuload += $load;
	}
	$cpuload = round($cpuload, 1); //One decimal place
	
	
	
	
	
	
	
	
	$q="INSERT INTO rpi_temp(temp,time,cpu) VALUES('$temp','$dtime','$cpuload')";
	$db->exec($q);
	 
?>
