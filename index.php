<?php 
  require_once "config.php" ;
  require_once "funs.php" ;

  $network = Stats::network() ;
?>

<html>
  <head>
  <meta charset="utf-8">
	<!-- 	<link rel="stylesheet" href="style.css" media="screen" /> -->
  	<title>Raspberry Pi - Status</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['id', 'RPI Temp'], <?php echo Stats::temperatures() ; ?>  
        ]);

        var options = {
          title: 'Raspberry PI Temperature',legend:{position: 'in', alignment:'center'}
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
          ['id', 'CPU Load %'], <?php echo Stats::cpuLoads() ; ?>  
        ]);

        var options = {
          title: 'Raspberry PI CPU Load',legend:{position: 'in', alignment:'center'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_cpuhistory'));
        chart.draw(data, options);
      }
    </script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
        function drawChart() {
          var options = {
            //height: 300,
            redFrom: 75, redTo: 90,
            yellowFrom:60, yellowTo: 75,
            greenFrom:0,greenTo:60,
            minorTicks: 5, min:0,max:90,
          };

          var chart = new google.visualization.Gauge(document.getElementById('chart_nowmeter'));
          
          var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['T', <?php echo Stats::temperature();?> ], //initial value from PHP
          ]);

          chart.draw(data, options);

          //Regular updates of the gauge
          setInterval(function () {
            var xhReq = new XMLHttpRequest();
            xhReq.onreadystatechange = function() {
              if(this.readyState!=4 || this.status != 200)
                return; //not ready / bad answer

              var temp = xhReq.responseText.trim();
              var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['T', parseFloat(temp) ], //new value attained by AJAX
              ]);
            
              chart.draw(data, options);
            }
            xhReq.open("GET", <?php echo Stats::temperatureURL();?>, true);
            xhReq.send(null);
          },1000);
        }
    </script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var options = {
          //height: 300,
          redFrom: 70, redTo: 100,
          yellowFrom:50, yellowTo: 70,
          greenFrom:0,greenTo:20,
          minorTicks: 5, min:0,max:100
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_cpunowmeter'));

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['CPU', <?php echo Stats::cpuLoad();?>], //initial value from PHP  
        ]);

        chart.draw(data, options);

        //Regular updates of the gauge        
        setInterval(function () {
          var xhReq = new XMLHttpRequest();
          xhReq.onreadystatechange = function() {
            if(this.readyState!=4 || this.status != 200)
              return; //not ready / bad answer

            var cpu = this.responseText.trim();
            var data = google.visualization.arrayToDataTable([
              ['Label', 'Value'],
              ['CPU', parseFloat(cpu) ], //new value attained by AJAX
            ]);
              
            chart.draw(data, options);
          }
          xhReq.open("GET", <?php echo Stats::cpuURL();?>, true);
          xhReq.send(null);
        },1000);
      }
    </script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'When');
        data.addColumn('number', 'DL');
        data.addColumn('number', 'UL');
        data.addRows([
          
          ['This Month',<?php echo "{v:{$network['mon0rx']},f:'{$network['mon0rx']} MB'} , {v:{$network['mon0tx']},f:'{$network['mon0tx']} MB'}"; ?>],
          ['Prev. Month', <?php echo "{v:{$network['mon1rx']},f:'{$network['mon1rx']} MB'} , {v:{$network['mon1tx']},f:'{$network['mon1tx']} MB'}"; ?>],
          ['Total', <?php echo "{v:{$network['alltrx']},f:'{$network['alltrx']} MB'} , {v:{$network['allttx']},f:'{$network['allttx']} MB'}"; ?>]

        ]);
        
        var options = {
        	sort:'disable',showRowNumber: false,width:440
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
          ['Day', 'Download (mb)', 'Upload (mb)'],
          ['Today',  <?php echo "{$network['day0rx']},{$network['day0tx']}";?>],
          ['-1 day',   <?php echo "{$network['day1rx']},{$network['day1tx']}";?>],
          ['-2 days',  <?php echo "{$network['day2rx']},{$network['day2tx']}";?>]
        ]);

        var options = {
          title: 'Daily Traffic',height:158,width:500,legend:{position: 'in', alignment:'center'}
          
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('traffic_graph'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
	<style>
	<?php include "style.css" ?>
	</style>
  	<div id="temp">
  		<div id="chart_nowmeter"></div>
  		<div id="chart_cpunowmeter"></div>
  		<div id="traffic">
  			<div id="traffic_graph"></div>
  			<!--div style="clear:both"></div-->
  			<div id="traffic_div"></div>
  			
  		</div>
  		<div style="clear: both;"></div>
  	</div>
    <div id="chart_temphistory"></div>
    <div id="chart_cpuhistory"></div>
    
  </body>
</html>
