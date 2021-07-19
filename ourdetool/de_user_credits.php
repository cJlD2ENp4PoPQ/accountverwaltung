<?php
include "../inccon.php";
include "../inc/sv.inc.php";
?>

<html>
<head>
<title>Creditverwaltung<</title>
<?php include "cssinclude.php";?>
</head>
<body>
<div align="center">
<h1>Creditverwaltung</h1>
<?
include "det_userdata.inc.php";

$uid=intval($_REQUEST['uid']);
$cg=intval($_REQUEST['cg']);

if($cg AND $uid)
{
  //alle accounts pa status nehmen
  mysql_query("UPDATE ls_user SET credits=credits+'$cg' WHERE user_id='$uid'");
  echo '<br>Die Credits wurden gutgeschrieben.<br>';
  
  //credits dem werber gutschreiben
  //werber auslesen
  $db_daten=mysql_query("SELECT werberid FROM ls_user WHERE user_id = '$uid'",$db);
  $num = mysql_num_rows($db_daten);
  if($num==1)
  {
    $row = mysql_fetch_array($db_daten);
    $werberid=$row["werberid"];
    $werbercredits=round($cg/10);
    if($werberid>0)
    {
      mysql_query("UPDATE ls_user SET credits=credits+'$werbercredits' WHERE user_id = '$werberid'",$db);
      //transfer mitloggen
      $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
      $clog="Zeit: $datum\n".$werbercredits.' Credit(s) wurden durch das '.$det_username.' als Werbebonus ('.$uid.')gutgeschrieben'."\n--------------------------------------\n";
      $fp=fopen("../cache/creditlogs/$werberid.txt", "a");
      fputs($fp, $clog);
      fclose($fp);
      //db-eintrag beim werber machen
      $time=time();
      mysql_query("INSERT INTO ls_credits SET user_id='$werberid', from_user='$uid', time='$time', credits='$werbercredits', typ=1, seen=0",$db);
    }
  }
  
  
  //eintrag wer wieviel gutgeschrieben hat in der billing-logdatei
  $datum=date("Y-m-d H:i:s",time());
  $clog="Zeit: $datum\n".$cg.' Credit(s) wurden durch '.$det_username.' hinterlegt.'."\n--------------------------------------\n";
  $fp=fopen("../cache/creditlogs/$uid.txt", "a");
  fputs($fp, $clog);
  fclose($fp);
}

//überschrift ausgeben
echo '<form action="de_user_credits.php" method="post">';
//user id im hiddenfield
echo '<input type="hidden" name="uid" value="'.$uid.'">';
//anzeige wieviel credits er momentan hat
$db_data = mysql_query("SELECT credits FROM ls_user WHERE user_id='$uid'",$db);
$row = mysql_fetch_array($db_data);
echo 'Kontostand: '.$row["credits"].'<br><br>';



echo 'Gutschrift: <input type="text" name="cg" size="5" value="0"><br>';
echo '<input type="Submit" name="b1" value="Credits gutschreiben">';
echo '</form>';
//wenn es ein billing-server ist, dann eine preisliste ausageben

if ($sv_pcs_id>0)
{
  echo '<br>SMS: 250';
  echo '<br>Anruf: 250';

  echo '<br>Überweisung:<br>
  1500 Credits - 10 Euro<br>
  3250 Credits - 20 Euro<br>
  5000 Credits - 30 Euro<br>
  6750 Credits - 40 Euro<br>
  8500 Credits - 50 Euro<br>';

  echo '<br><a href="de_user_credits.php?showlog=1&uid='.$uid.'">Billing-Logdatei anzeigen</a><br><br>';
  
  if($_REQUEST["showlog"])
  {
  	//zuerst schauen ob der user existiert
    $filename = '../cache/creditlogs/'.$uid.'.txt';
    if (file_exists($filename))
    {
      //logdatei ausgeben
      $filename = str_replace("://","a",$filename);
      $filename = str_replace("php","a",$filename);
      echo '<br><b>Logfile von '.$uid.'</b><br><br>';
      $fp = fopen($filename, 'rb');
      while (!feof($fp))
      {
        $buffer = fread($fp, 1024);
        $buffer = str_replace("\n","<br>",$buffer);
        echo $buffer;
      }
      fclose($fp);
    }
    else echo "Zu dem User existiert keine Logdatei.";
  }
  
}
?>
</div>
</body>
</html>

