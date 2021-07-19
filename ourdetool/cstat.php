<?php
include "../inccon.php";
include "det_userdata.inc.php";
?>
<html>
<head>
<title>Statistik</title>
<?php include "cssinclude.php";?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Zeitraum', 'xDE', 'SDE', 'RDE','EDE','CDE','EA1','AND1','premium']
<?php

$starjahr=date("Y")-3;

for($jahr=$starjahr;$jahr<=date("Y");$jahr++){

  $sql="SELECT SUM(xDE) AS xDE, SUM(SDE) AS SDE, SUM(RDE) AS RDE, SUM(DEDV) AS DEDV, SUM(NDE) AS NDE, SUM(QDE) AS QDE, SUM(ENSDE) AS ENSDE, SUM(BGDE) AS BGDE, SUM(EDE) AS EDE, SUM(CDE) AS CDE, SUM(NSE) AS NSE, SUM(SSE) AS SSE, SUM(EA1) AS EA1, SUM(ALU1) AS ALU1, SUM(AND1) AS AND1, SUM(premium) AS premium FROM ls_credit_use WHERE datum LIKE '$jahr%'";
  //echo $sql;
  $db_daten=mysql_query($sql,$db); 
  $row = mysql_fetch_array($db_daten);

  echo ",['$jahr', ".$row['xDE'].", ".$row['SDE'].", ".$row['RDE'].", ".$row['EDE'].", ".$row['CDE'].", ".$row['EA1'].", ".$row['AND1'].", ".$row['premium']."]";

}
?>

        ]);

        var options = {
          title: 'Creditstatistik',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('credit_chart'));

        chart.draw(data, options);

		/////////////////////////////
		// userreg
		/////////////////////////////

        var data = google.visualization.arrayToDataTable([
          ['Zeitraum', 'Registrierungen']
<?php

$starjahr=date("Y")-3;

for($jahr=$starjahr;$jahr<=date("Y");$jahr++){

  $sql="SELECT COUNT(*) AS anzahl FROM ls_user WHERE register LIKE '$jahr%'";
  //echo $sql;
  $db_daten=mysql_query($sql,$db); 
  $row = mysql_fetch_array($db_daten);

  echo ",['$jahr', ".$row['anzahl']."]";

}
?>

        ]);

        var options = {
          title: 'Anmeldungen',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('userreg_chart'));

        chart.draw(data, options);

		/////////////////////////////
		// userreg Monat
		/////////////////////////////
<?php

$starjahr=date("Y")-3;

echo  "var data = google.visualization.arrayToDataTable([
	['Zeitraum', '".($starjahr+0)."', '".($starjahr+1)."', '".($starjahr+2)."', '".($starjahr+3)."']";


for($monat=1;$monat<=12; $monat++){
	$anz=array();
	for($jahr=$starjahr;$jahr<=date("Y");$jahr++){
		$monat_str=$monat;
		if($monat_str<10){
			$monat_str='0'.$monat_str;
		}

		$sql="SELECT COUNT(*) AS anzahl FROM ls_user WHERE register LIKE '$jahr-".$monat_str."%'";
		//echo $sql;
		$db_daten=mysql_query($sql,$db); 
		$row = mysql_fetch_array($db_daten);
		$anz[]=$row['anzahl'];
	}

	echo ",['$monat	', ".$anz[0].", ".$anz[1].", ".$anz[2].", ".$anz[3]."]";  

}
?>

        ]);

        var options = {
          title: 'Anmeldungen',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('userreg_month_chart'));

        chart.draw(data, options);



      }


    </script>


</head>
<body>
<div align="center">

<div id="userreg_month_chart" style="width: 1000px; height: 500px"></div>
<br>
<div id="userreg_chart" style="width: 1000px; height: 500px"></div>
<br>
<div id="credit_chart" style="width: 1000px; height: 500px"></div>



</div>

</body>
</html>
