<?php
include "../inccon.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Serverstatistik</title>
</head>
<body>
Jahresstatistik
<table>
<tr>
<td>Datum</td>
<td>Neuregistrierungen</td>
</tr>
<?php
//für jeden tag die regs auslesen, maximal 1 jahr
$anzahl1ges=0;
$anzahl2ges=0;
$time=time();
$datumletzterlogin=date("Y-m-d",$time-(7*24*3600));
for($i=0;$i<=365;$i++)
{
  $datum=date("Y-m-d",$time);
  
  $result1 = mysql_query("SELECT COUNT(*) AS anzahl FROM ls_user WHERE register LIKE '$datum%'",$db);
  $row1 = mysql_fetch_array($result1);

  $result2 = mysql_query("SELECT COUNT(*) AS anzahl FROM ls_user WHERE register LIKE '$datum%' AND last_login>'$datumletzterlogin'",$db);
  $row2 = mysql_fetch_array($result2);
  
  $anzahl1=$row1["anzahl"];
  $anzahl2=$row2["anzahl"];
  
  if($i>7)
  {
    $anzahl1ges+=$anzahl1;
    $anzahl2ges+=$anzahl2;
  }
  
  echo '<tr>';
  echo '<td><font size="1">'.$datum.'</td>';
  echo '<td><font size="1"><img src="blue.gif" width="'.round($anzahl1).'" height="8"> '.$anzahl1.' ('.$anzahl2.')</td>';
  echo '</tr>';

  $time=$time-(3600*24);
}
echo '</table>';

echo 'registriert: '.$anzahl1ges.' - noch dabei: '.$anzahl2ges;

?>
