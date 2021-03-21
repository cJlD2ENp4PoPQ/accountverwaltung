<?php
include 'content/de/lang/'.$ums_language.'_getplaytime.lang.php';

//tage credits
$spielzeitkosten[0][0]=30;
$spielzeitkosten[0][1]=400;
$spielzeitkosten[1][0]=60;
$spielzeitkosten[1][1]=780;
$spielzeitkosten[2][0]=90;
$spielzeitkosten[2][1]=1150;
$spielzeitkosten[3][0]=120;
$spielzeitkosten[3][1]=1500;

//credits transferieren
if (($_POST[playtime] OR $_POST[button])AND $serverdata[$_REQUEST["server"]][7]==2)
{
  $playtime=intval($_REQUEST["playtime"]);
  $target=intval($_REQUEST["server"]);
  //schauen ob etwas ausgew�hlt wurde
  //if($playtime>=1 AND $playtime<=4)
  //{
    //beim ticket immer 1
  	$playtime=1;
  	//datensatz locken und erst dann weitermachen, wenn genug credits drauf sind
    $result = mysql_query("UPDATE ls_user SET tupdate=1 WHERE user_id='$ums_user_id' AND tupdate=0", $db);
    $num = mysql_affected_rows();
    if($num==1)
    {
      //schauen wieviel tickets man hat
	  $result = mysql_query("SELECT tickets FROM ls_user WHERE user_id='$ums_user_id';");
	  $db_data = mysql_fetch_array($result);
	  $tickets=$db_data["tickets"];

      if($tickets>0)
	  {
        //spielzeit buchen
        $result=doPost($serverdata[$target][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&getplaytime=1&id='.$ums_user_id.'&tage='.$spielzeitkosten[$playtime-1][0], $serverdata[$target][5]);
        if($result==1)
	  	{
	  	  //message alles ok
	  	  $errmsg.='<font color="00FF00">'.$getplaytime_lang['msg_1'].'</font>';
	  	  //tickets beim hauptaccount abziehen
          mysql_query("UPDATE ls_user SET tickets=tickets-1 WHERE user_id='$ums_user_id'", $db);
          //transfer mitloggen
		  $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

  		  $clog=$getplaytime_lang['msg_2_1'].": $datum\n".$getplaytime_lang['msg_2_2'].": $ip\n".$tcredits.' '.$getplaytime_lang['msg_2_3'].' '.$serverdata[$_REQUEST["server"]][0].' '.$getplaytime_lang['msg_2_4']."\n--------------------------------------\n";
  		  $fp=fopen("cache/creditlogs/$ums_user_id.txt", "a");
          fputs($fp, $clog);
          fclose($fp);
	  	}else $errmsg.='<font color="FF0000">'.$getplaytime_lang['msg_3'].'</font>';
	  }else $errmsg.='<font color="FF0000">'.$getplaytime_lang['msg_4'].'</font>';
    }else $errmsg.='<font color="FF0000">'.$getplaytime_lang['msg_5'].'</font>';
  //}else $errmsg.='<font color="FF0000">W�hle bitte ein Angebot aus.</font>';
  //lock entfernen
  mysql_query("UPDATE ls_user SET tupdate=0 WHERE user_id='$ums_user_id'", $db);
}


if($errmsg!='')echo $errmsg;

//spielzeitkauf geht nur auf servern wo dieses angeboten wird
if($serverdata[$_REQUEST["server"]][7]==2)
{
//daten auslesen
//credits im hauptaccount
$result = mysql_query("SELECT tickets FROM ls_user WHERE user_id='$ums_user_id';");
$db_data = mysql_fetch_array($result);
$tickets=$db_data["tickets"];

//pa-zeit auf dem spielserver
$result=doPost($serverdata[$_REQUEST["server"]][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&getaccountdata=1&id='.$ums_user_id, $serverdata[$_REQUEST["server"]][5]);
$explode=explode(";",$result);
$patime=$explode[7];
if($patime<time())$palz=$getplaytime_lang['abgelaufen'];
else $palz=date("d.m.Y - G:i", $patime);


echo '<form action="index.php?command=getplaytime&server='.$_REQUEST["server"].'" method="POST">';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$getplaytime_lang['spielzeiterwerb'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$getplaytime_lang['msg_6_1'].' '.$serverdata[$_REQUEST["server"]][0].'-'.$getplaytime_lang['msg_6_2'].'.</td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$getplaytime_lang['msg_7'].'</td>
      </tr>
      <tr>
        <td width="180">'.$getplaytime_lang['tickets'].':</td>
        <td width="200">'.number_format($tickets, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$getplaytime_lang['vorhandespielzeitbis'].':</td>
        <td>'.$palz.'</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="Submit" name="button" value="'.$getplaytime_lang['ticketeinloesen'].'"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><br>'.$getplaytime_lang['msg_8'].'</td>
      </tr>
      ';

echo '</table>';
echo '</form>';
}
else echo $getplaytime_lang['msg_9'];


?>