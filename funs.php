<?php
	require_once "db.php" ;

	class Stats {

		public static function cpuLoads() {
			$buf = "" ;
			$results = DB::query('SELECT * FROM rpi_temp ORDER BY id DESC LIMIT 0,288');
			while ($row = $results->fetchArray()) {
				$id = $row['id'];
				$cpu=$row['cpu'];
				$dtime=substr($row['time'], -8, 5);
				$cpu=$row['cpu'];
				
				$buf .= "['$dtime',$cpu],";  
				
			}
			return $buf ;
		}

		public static function cpuLoad() {
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
			return $cpuload ;
		}

		public static function temperature() {
			$temp = round(exec("cat /sys/class/thermal/thermal_zone0/temp ") / 1000, 1) ;
			return $temp ;
		}

		public static function temperatures() {
			$buf = "";
			$results = DB::query("SELECT * FROM rpi_temp ORDER BY id DESC LIMIT 0,288");
			while ($row = $results->fetchArray()) {
				$id = $row['id'];
				$temp=$row['temp'];
				$dtime=substr($row['time'], -8, 5);
				$cpu=$row['cpu'];
				
				$buf .= "['$dtime',$temp],";  
				
			}

			return $buf ;
		}

		public static function measureTemp() {
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
			DB::exec($q);
		}


		public static function network() {
			$network = [];
			//traffic
			$traffic=null;
			exec("vnstat --dumpdb", $traffic);
			$network['alltrxL']=explode(";",$traffic[6]);
			$network['allttxL']=explode(";",$traffic[7]);	
			
			$network['alltrx'] = $network['alltrxL'][1];
			$network['allttx'] = $network['allttxL'][1];
			//$alltrxtx = $alltrx+$allttx;
			
			$network['day0'] = explode(";",$traffic[13]);
			$network['day0rx'] = $network['day0'][3];
			$network['day0tx'] = $network['day0'][4];
			
			$network['day1'] = explode(";",$traffic[14]);
			$network['day1rx'] = $network['day1'][3];
			$network['day1tx'] = $network['day1'][4];
			
			$network['day2'] = explode(";",$traffic[15]);
			$network['day2rx'] = $network['day2'][3];
			$network['day2tx'] = $network['day2'][4];
			
			$network['mon0'] = explode(";",$traffic[43]);
			$network['mon0rx'] = $network['mon0'][3];
			$network['mon0tx'] = $network['mon0'][4];
			
			$network['mon1'] = explode(";",$traffic[44]);
			$network['mon1rx'] = $network['mon1'][3];
			$network['mon1tx'] = $network['mon1'][4];

			//var_dump($network);
			return $network ;
		}
	}


?>
