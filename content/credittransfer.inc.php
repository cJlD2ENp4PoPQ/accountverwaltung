<?php
include 'content/de/lang/'.$ums_language.'_credittransfer.lang.php';

//credits transferieren
if (($_POST[tcredits] OR $_POST[button])AND $serverdata[$_REQUEST["server"]][7]==1)
{
  $target=intval($_REQUEST["server"]);
  //datensatz locken und erst dann weitermachen, wenn genug credits drauf sind
  $result = mysql_query("UPDATE ls_user SET tupdate=1 WHERE user_id='$ums_user_id' AND tupdate=0", $db);
  $num = mysql_affected_rows();
  if($num==1)
  {
    //schauen wieviel credits man hat
	$result = mysql_query("SELECT credits FROM ls_user WHERE user_id='$ums_user_id';");
	$db_data = mysql_fetch_array($result);
	$credits=$db_data["credits"];
    //wieviel credits solle transferiert werden
    $tcredits=intval($_REQUEST["tcredits"]);

    //es muss mindestens 1 credit transferiert werden
    if($tcredits>0)
    {
	  if($tcredits<=$credits)
	  {
        //credits auf den account transferieren
        $result=doPost($serverdata[$target][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&credittransfer=1&id='.$ums_user_id.'&credits='.$tcredits, $serverdata[$target][5]);
        if($result==1)
	  	{
	  	  //message alles ok
	  	  $errmsg.='<font color="00FF00">'.$credittransfer_lang['msg_1'].'</font>';
	  	  //credits beim hauptaccount abziehen
          mysql_query("UPDATE ls_user SET credits=credits-'$tcredits' WHERE user_id='$ums_user_id'", $db);
          
          //transfer beim user mitloggen
		  $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];


  		  $clog=$credittransfer_lang['msg_2_1'].": $datum\n".$credittransfer_lang['msg_2_2'].": $ip\n".$tcredits.' '.$credittransfer_lang['msg_2_3'].' '.$serverdata[$_REQUEST["server"]][0].' '.$credittransfer_lang['msg_2_4']."\n--------------------------------------\n";
  		  $fp=fopen("cache/creditlogs/$ums_user_id.txt", "a");
          fputs($fp, $clog);
          fclose($fp);
          
          //transfer f�r die statistik mitloggen
          $zeit=strftime("%Y-%m-%d");
          mysql_query("INSERT INTO ls_credit_use SET datum='$zeit'",$db);
          
          $spalte=$serverdata[$_REQUEST["server"]][0];
          $spalte=str_replace(":","",$spalte);

          mysql_query("UPDATE ls_credit_use SET $spalte=$spalte+'$tcredits' WHERE datum='$zeit'",$db);          
          
	  	}else $errmsg.='<font color="FF0000">'.$credittransfer_lang['msg_3'].'</font>';
	  }else $errmsg.='<font color="FF0000">'.$credittransfer_lang['msg_4'].'</font>';
    }else $errmsg.='<font color="FF0000">'.$credittransfer_lang['msg_5'].'</font>';
  }else $errmsg.='<font color="FF0000">'.$credittransfer_lang['msg_6'].'</font>';
  //lock entfernen
  mysql_query("UPDATE ls_user SET tupdate=0 WHERE user_id='$ums_user_id'", $db);
}


if($errmsg!='')echo $errmsg;

//ein transfer geht nur auf servern wo dieses angeboten wird
if($serverdata[$_REQUEST["server"]][7]==1)
{
//daten auslesen
//credits im hauptaccount
$result = mysql_query("SELECT credits FROM ls_user WHERE user_id='$ums_user_id';");
$db_data = mysql_fetch_array($result);
$credits=$db_data["credits"];

//credits auf dem spielserver
$result=doPost($serverdata[$_REQUEST["server"]][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&getaccountdata=1&id='.$ums_user_id, $serverdata[$_REQUEST["server"]][5]);
$explode=explode(";",$result);
$creditsserver=$explode[6];


echo '<form action="index.php?command=credittransfer&server='.$_REQUEST["server"].'" method="POST">';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$credittransfer_lang['credittransfer'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$credittransfer_lang['msg_7_1'].' '.$serverdata[$_REQUEST["server"]][0].'-'.$credittransfer_lang['msg_7_2'].'</td>
      </tr>
      <tr>
        <td width="180">'.$credittransfer_lang['creditsimhauptaccount'].':</td>
        <td width="200">'.number_format($credits, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$credittransfer_lang['msg_8_1'].' '.$serverdata[$_REQUEST["server"]][0].'-'.$credittransfer_lang['msg_8_2'].':</td>
        <td>'.number_format($creditsserver, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$credittransfer_lang['credittransfermenge'].':</td>
        <td><input type="text" name="tcredits" value=""></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="Submit" name="button" value="'.$credittransfer_lang['transferstarten'].'"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><br>'.$credittransfer_lang['msg_9'].'</td>
      </tr>
      ';

echo '</table>';
echo '</form>';
}
else echo $credittransfer_lang['msg_10'];


?>