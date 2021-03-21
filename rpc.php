<?php
$disablegzip=1;
include "inccon.php";

//jeden aufruf �ber einen key checken
if($_POST["authcode"]!=$GLOBALS['env_rpc_authcode'])exit;

//feststellen ob es einen account mit der user_id gibt
if($_POST["isaccount_user_id"]==1)
{
  $id=intval($_POST["id"]);
  if($id==0)$id='-1';
  $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE user_id='$id'",$db);
  $num = mysql_num_rows($db_daten);
  if($num==1)
  {
    echo '1';
  }
  else echo '0';
}

//credittransfer durchs billingsystem
if($_POST["credittransfer_billing"]==1)
{
  $user_id=intval($_POST["id"]);
  $credits=intval($_POST["credits"]);
  if($id==0)$id='-1';

  //credits dem user gutschreiben  
  mysql_query("UPDATE ls_user SET credits=credits+'$credits' WHERE user_id = '$user_id'",$db);
  //credits dem werber gutschreiben
  //werber auslesen
  $db_daten=mysql_query("SELECT werberid FROM ls_user WHERE user_id = '$user_id'",$db);
  $num = mysql_num_rows($db_daten);
  if($num==1)
  {
    $row = mysql_fetch_array($db_daten);
    $werberid=$row["werberid"];
    $werbercredits=round($credits/10);
    if($werberid>0)
    {
      mysql_query("UPDATE ls_user SET credits=credits+'$werbercredits' WHERE user_id = '$werberid'",$db);
      //transfer mitloggen
      $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

      $clog="Zeit: $datum\n".$werbercredits.' Credit(s) wurden durch das Billing-System als Werbebonus ('.$user_id.')gutgeschrieben'."\n--------------------------------------\n";
      $fp=fopen("cache/creditlogs/$werberid.txt", "a");
      fputs($fp, $clog);
      fclose($fp);    
      //db-eintrag beim werber machen
      $time=time();
      mysql_query("INSERT INTO ls_credits SET user_id='$werberid', from_user='$user_id', time='$time', credits='$werbercredits', typ=1, seen=0",$db);
    }
  }
  
  //transfer mitloggen
  $datum=date("Y-m-d H:i:s",time());
  $ip=getenv("REMOTE_ADDR");
  $clog="Zeit: $datum\n".$credits.' Credit(s) wurden durch das Billing-System gutgeschrieben'."\n--------------------------------------\n";
  $fp=fopen("cache/creditlogs/$user_id.txt", "a");
  fputs($fp, $clog);
  fclose($fp);    
  echo '1'; //status 1 = fertig
}

?>
