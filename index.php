<?php include "config.php" ?>

<?php 
//traffic
	$traffic=null;
	exec("vnstat --dumpdb", $traffic);
	$alltrxL=explode(";",$traffic[6]);
	$allttxL=explode(";",$traffic[7]);	
	
	$alltrx = $alltrxL[1];
	$allttx = $allttxL[1];
	//$alltrxtx = $alltrx+$allttx;
	
	$day0 = explode(";",$traffic[13]);
	$day0rx = $day0[3];
	$day0tx = $day0[4];
	
	$day1 = explode(";",$traffic[14]);
	$day1rx = $day1[3];
	$day1tx = $day1[4];
	
	$mon0 = explode(";",$traffic[43]);
	$mon0rx = $mon0[3];
	$mon0tx = $mon0[4];
	
	$mon1 = explode(";",$traffic[44]);
	$mon1rx = $mon1[3];
	$mon1tx = $mon1[4];
	

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

?>

<html>
  <head>
  <meta charset="utf-8">
  	<link rel="stylesheet" href="style.css" media="screen" />
  	<title>Raspberry Pi - Status</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'When');
        data.addColumn('number', 'DL');
        data.addColumn('number', 'UL');
        data.addRows([
          ['Today', <?php echo "{v:$day0rx,f:'$day0rx MB'} , {v:$day0tx,f:'$day0tx MB'}"; ?>],
          ['Yesterday',<?php echo "{v:$day1rx,f:'$day1rx MB'} , {v:$day1tx,f:'$day1tx MB'}"; ?>],
          ['This Month',<?php echo "{v:$mon0rx,f:'$mon0rx MB'} , {v:$mon0tx,f:'$mon0tx MB'}"; ?>],
          ['Prev. Month', <?php echo "{v:$mon1rx,f:'$mon1rx MB'} , {v:$mon1tx,f:'$mon1tx MB'}"; ?>],
          ['Total', <?php echo "{v:$alltrx,f:'$alltrx MB'} , {v:$allttx,f:'$allttx MB'}"; ?>]

        ]);
        
        var options = {
        	sort:'disable',showRowNumber: false,width:500
        
        };

        var table = new google.visualization.Table(document.getElementById('traffic_div'));
        table.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['id', 'temperature'],
		  
			<?php 
				$db = new SQLite3($config["temperature_DB"]);
				$results = $db->query('SELECT * FROM rpi_temp ORDER BY id DESC LIMIT 0,288');
				while ($row = $results->fetchArray()) {
					$id = $row['id'];
					$temp=$row['temp'];
					$dtime=substr($row['time'], -8, 5);
					$cpu=$row['cpu'];
					
					echo "['$dtime',$temp],";  
					
				}
			?>
  
        ]);

        var options = {
          title: 'Raspberry PI Temperature'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_temphistory'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['id', 'CPU Load %'],
		  
			<?php 
				
				$results = $db->query('SELECT * FROM rpi_temp ORDER BY id DESC LIMIT 0,288');
				while ($row = $results->fetchArray()) {
					$id = $row['id'];
					$cpu=$row['cpu'];
					$dtime=substr($row['time'], -8, 5);
					$cpu=$row['cpu'];
					
					echo "['$dtime',$cpu],";  
					
				}
			?>
  
        ]);

        var options = {
          title: 'Raspberry PI CPU Load'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_cpuhistory'));
        chart.draw(data, options);
      }
    </script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['T', <?php echo round(exec("cat /sys/class/thermal/thermal_zone0/temp ") / 1000, 1);?>],
          
        ]);

        var options = {
         height: 300,
          redFrom: 75, redTo: 90,
          yellowFrom:60, yellowTo: 75,
          greenFrom:0,greenTo:60,
          minorTicks: 5, min:0,max:90
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_nowmeter'));
        chart.draw(data, options);
      }
    </script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['CPU', <?php echo $cpuload;?>],
          
        ]);

        var options = {
         height: 300,
          redFrom: 70, redTo: 100,
          yellowFrom:50, yellowTo: 70,
          greenFrom:0,greenTo:20,
          minorTicks: 5, min:0,max:100
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_cpunowmeter'));
        chart.draw(data, options);
      }
    </script>
    
  </head>
  <body>
  	<div id="temp">
  		<div id="chart_nowmeter"></div>
  		<div id="chart_cpunowmeter"></div>
  		<div id="traffic">
  			<div id="traffic_div"></div>
  		</div>
  	</div>
    <div id="chart_temphistory"></div>
    <div id="chart_cpuhistory"></div>
    
  </body>
</html>
