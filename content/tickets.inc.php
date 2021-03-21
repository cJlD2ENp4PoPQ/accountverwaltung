<?php
include 'content/de/lang/'.$ums_language.'_tickets.lang.php';

//tage credits
$spielzeitkosten[0][0]=1;
$spielzeitkosten[0][1]=400;
$spielzeitkosten[1][0]=2;
$spielzeitkosten[1][1]=780;
$spielzeitkosten[2][0]=3;
$spielzeitkosten[2][1]=1150;
$spielzeitkosten[3][0]=4;
$spielzeitkosten[3][1]=1500;

//ticket kaufen
if ($_POST[playtime] OR $_POST[button] OR $_POST[sonder])
{
  $playtime=intval($_REQUEST["playtime"]);
  //schauen ob etwas ausgew�hlt wurde
  //fix f�r sonderaktion, wird sp�ter extra behandelt
  if($_POST[sonder])$playtime=1;
  if($playtime>=1 AND $playtime<=4)
  {
    //datensatz locken und erst dann weitermachen, wenn genug credits drauf sind
    $result = mysql_query("UPDATE ls_user SET tupdate=1 WHERE user_id='$ums_user_id' AND tupdate=0", $db);
    $num = mysql_affected_rows();
    if($num==1)
    {
      //schauen wieviel credits man hat
	  $result = mysql_query("SELECT credits, sonderaktion FROM ls_user WHERE user_id='$ums_user_id';");
	  $db_data = mysql_fetch_array($result);
	  $credits=$db_data["credits"];
	  $sonderaktion=$db_data["sonderaktion"];
      //wieviel credits werden ben�tigt
      $tcredits=$spielzeitkosten[$playtime-1][1];

      if($tcredits<=$credits)
	  {
        //schauen ob er die sonderaktion nutzen m�chte
        if($_POST[sonder])
        {
	  	  if($sonderaktion==0)
	  	  {
            //flag setzen
	  	    mysql_query("UPDATE ls_user SET sonderaktion=1 WHERE user_id='$ums_user_id'", $db);
	  	    //variablen neu setzen
	  	    //kosten
	  	    $tcredits=400;
	  	    //tickets
	  	    $playtime=6;
	  	  }else $errmsg.='<font color="FF0000">'.$tickets_lang['msg_1'].'</font>';
        }

        //wenn keine fehler auftreten die sachen buchen
        if($errmsg=='')
        {
          //message alles ok
	  	  $errmsg.='<font color="00FF00">'.$tickets_lang['msg_2'].'</font>';
	  	  //credits beim hauptaccount abziehen und tickets gutschreiben
          mysql_query("UPDATE ls_user SET credits=credits-'$tcredits', tickets=tickets+'$playtime' WHERE user_id='$ums_user_id'", $db);
          //transfer mitloggen
		  $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

  		  $clog="Zeit: $datum\nIP: $ip\n".$tcredits.' Credit(s) wurden f�r '.$playtime.' Tickets verbraucht'."\n--------------------------------------\n";
  		  $fp=fopen("cache/creditlogs/$ums_user_id.txt", "a");
          fputs($fp, $clog);
          fclose($fp);
        }
	  }else $errmsg.='<font color="FF0000">'.$tickets_lang['msg_3'].'</font>';
    }else $errmsg.='<font color="FF0000">'.$tickets_lang['msg_4'].'</font>';
  }else $errmsg.='<font color="FF0000">'.$tickets_lang['msg_5'].'</font>';
  //lock entfernen
  mysql_query("UPDATE ls_user SET tupdate=0 WHERE user_id='$ums_user_id'", $db);
}


if($errmsg!='')echo $errmsg;

//daten auslesen
//credits im hauptaccount
$result = mysql_query("SELECT credits, tickets, sonderaktion FROM ls_user WHERE user_id='$ums_user_id';");
$db_data = mysql_fetch_array($result);
$credits=$db_data["credits"];
$tickets=$db_data["tickets"];
$sonderaktion=$db_data["sonderaktion"];

echo '<form action="index.php?command=tickets" method="POST">';
echo '<table width="500" border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$tickets_lang['ticketerwerb'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$tickets_lang['msg_6'].'</td>
      </tr>
      <tr>
        <td width="50%">'.$tickets_lang['creditsimhauptaccount'].':</td>
        <td width="50%">'.number_format($credits, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$tickets_lang['ticketsimhauptaccount'].':</td>
        <td>'.number_format($tickets, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$tickets_lang['spielzeitangebot'].':</td>
        <td>
		  <select name="playtime">
		  <option selected>'.$tickets_lang['bitteauswaehlen'].'</option>
		  <option value="1">'.$spielzeitkosten[0][0].' '.$tickets_lang['ticket'].' ('.($spielzeitkosten[0][0]*30).' '.$tickets_lang['tage'].') - '.$spielzeitkosten[0][1].' '.$tickets_lang['credits'].'</option>
		  <option value="2">'.$spielzeitkosten[1][0].' '.$tickets_lang['tickets'].' ('.($spielzeitkosten[1][0]*30).' '.$tickets_lang['tage'].') - '.$spielzeitkosten[1][1].' '.$tickets_lang['credits'].'</option>
	      <option value="3">'.$spielzeitkosten[2][0].' '.$tickets_lang['tickets'].' ('.($spielzeitkosten[2][0]*30).' '.$tickets_lang['tage'].') - '.$spielzeitkosten[2][1].' '.$tickets_lang['credits'].'</option>
	      <option value="4">'.$spielzeitkosten[3][0].' '.$tickets_lang['tickets'].' ('.($spielzeitkosten[3][0]*30).' '.$tickets_lang['tage'].') - '.$spielzeitkosten[3][1].' '.$tickets_lang['credits'].'</option>
		  </select>

        </td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><input type="Submit" name="button" value="'.$tickets_lang['ticketerwerben'].'"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><br>'.$tickets_lang['msg_7'].'</td>
      </tr>';

echo '</table>';
echo '</form>';

/*
echo '<form action="index.php?command=tickets" method="POST">';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td width="550" align="center"><b>'.$tickets_lang['sonderangebot'].'</b></td>
      </tr>
      <tr>
        <td align="center">'.$tickets_lang['msg_8'].'</td>
      </tr>
      <tr>
        <td align="center">'.$tickets_lang['msg_9'].'</td>
      </tr>';
if($sonderaktion==0)
echo '<tr>
        <td align="center"><input type="Submit" name="sonder" value="'.$tickets_lang['sonderangebotnutzen'].'"></td>
      </tr>';
else
echo '<tr>
        <td align="center">'.$tickets_lang['msg_10'].'</td>
      </tr>';
echo '</table>';
echo '</form>';
*/


?>