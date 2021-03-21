<?php
include 'content/de/lang/'.$ums_language.'_premium.lang.php';

echo '<div style="width: 100%;">';

//tage credits
$spielzeitkosten[0][0]=1;
$spielzeitkosten[0][1]=300;
$spielzeitkosten[1][0]=2;
$spielzeitkosten[1][1]=590;
$spielzeitkosten[2][0]=3;
$spielzeitkosten[2][1]=870;
$spielzeitkosten[3][0]=4;
$spielzeitkosten[3][1]=1130;

//pa kaufen
if ($_POST[playtime] OR $_POST[button])
{
  $playtime=intval($_REQUEST["playtime"]);
  //schauen ob etwas ausgew�hlt wurde
  if($playtime>=1 AND $playtime<=4)
  {
    //datensatz locken und erst dann weitermachen, wenn genug credits drauf sind
    $result = mysql_query("UPDATE ls_user SET tupdate=1 WHERE user_id='$ums_user_id' AND tupdate=0", $db);
    $num = mysql_affected_rows();
    if($num==1)
    {
      //schauen wieviel credits man hat
	  $result = mysql_query("SELECT credits, patime FROM ls_user WHERE user_id='$ums_user_id';");
	  $db_data = mysql_fetch_array($result);
	  $credits=$db_data["credits"];
	  $patime=$db_data["patime"];

      //wieviel credits werden ben�tigt
      $tcredits=$spielzeitkosten[$playtime-1][1];

      if($tcredits<=$credits)
	  {
        //wenn keine fehler auftreten die sachen buchen
        if($errmsg=='')
        {
          //message alles ok
	  	  $errmsg.='<font color="00FF00">'.$premium_lang['msg_2'].'</font>';
          //pazeit gutschreiben
          if($patime<time())$patime=time();
          //pazeit= playtime(x monate) * 30 tage * 24 stunden * 3600 sekunden
          $patime+=$playtime*30*24*3600;

	  	  //credits beim hauptaccount abziehen und pazeit updaten
          mysql_query("UPDATE ls_user SET credits=credits-'$tcredits', patime='$patime' WHERE user_id='$ums_user_id'", $db);
          
          //jetzt die einzelnen server updaten
          for($i=0;$i<=$sindex;$i++){
                //echo '<br>'.$i.':';
                //echo 
                doPost($serverdata[$i][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&getplaytime=1&id='.$ums_user_id.'&tage='.$playtime*30, $serverdata[$i][5]);
          }
		  
          //transfer beim user mitloggen
		  $datum=date("Y-m-d H:i:s",time());
      $ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

  		  $clog="Zeit: $datum\nIP: $ip\n".$tcredits.' Credit(s) wurden für '.($playtime*30).' Tage Premiumaccount verbraucht'."\n--------------------------------------\n";
  		  $fp=fopen("cache/creditlogs/$ums_user_id.txt", "a");
          fputs($fp, $clog);
          fclose($fp);
          
          //transfer in der statistik hinterlgen
          $zeit=strftime("%Y-%m-%d");
          mysql_query("INSERT INTO ls_credit_use SET datum='$zeit'",$db);
          mysql_query("UPDATE ls_credit_use SET premium=premium+'$tcredits' WHERE datum='$zeit'",$db);
        }
	  }else $errmsg.='<font color="FF0000">'.$premium_lang['msg_3'].'</font>';
    }else $errmsg.='<font color="FF0000">'.$premium_lang['msg_4'].'</font>';
  }else $errmsg.='<font color="FF0000">'.$premium_lang['msg_5'].'</font>';
  //lock entfernen
  mysql_query("UPDATE ls_user SET tupdate=0 WHERE user_id='$ums_user_id'", $db);
}


if($errmsg!='')echo $errmsg;

//daten auslesen
//credits im hauptaccount
$result = mysql_query("SELECT credits, patime FROM ls_user WHERE user_id='$ums_user_id';");
$db_data = mysql_fetch_array($result);
$credits=$db_data["credits"];
$palaufzeit=$db_data["patime"];

//pa laufzeit berechnen
if($palaufzeit<time())$palzstr='-'; else $palzstr=date($premium_lang["dateformat"], $palaufzeit);

echo '<form action="index.php?command=premium" method="POST">';
echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$premium_lang['ticketerwerb'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$premium_lang['msg_6'].'</td>
      </tr>
      <tr>
        <td width="50%">'.$premium_lang['creditsimhauptaccount'].':</td>
        <td width="50%">'.number_format($credits, 0,"",".").'</td>
      </tr>
      <tr>
        <td>'.$premium_lang['palaufzeit'].':</td>
        <td>'.$palzstr.'</td>
      </tr>
      <tr>
        <td>'.$premium_lang['spielzeitangebot'].':</td>
        <td>
		  <select name="playtime">
		  <option selected>'.$premium_lang['bitteauswaehlen'].'</option>
		  <option value="1">'.($spielzeitkosten[0][0]*30).' '.$premium_lang['tage'].' - '.$spielzeitkosten[0][1].' '.$premium_lang['credits'].'</option>
		  <option value="2">'.($spielzeitkosten[1][0]*30).' '.$premium_lang['tage'].' - '.$spielzeitkosten[1][1].' '.$premium_lang['credits'].'</option>
	      <option value="3">'.($spielzeitkosten[2][0]*30).' '.$premium_lang['tage'].' - '.$spielzeitkosten[2][1].' '.$premium_lang['credits'].'</option>
	      <option value="4">'.($spielzeitkosten[3][0]*30).' '.$premium_lang['tage'].' - '.$spielzeitkosten[3][1].' '.$premium_lang['credits'].'</option>
		  </select>

        </td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><input type="Submit" name="button" value="'.$premium_lang['ticketerwerben'].'"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br><br>'.$premium_lang['msg_7'].'</td>
      </tr>';

echo '</table>';
echo '</form>';

echo '<br>'.$premium_lang['pavorteile'].'<br>';

$okgrafik='<img src="img/button_ok.gif" border="0">';

$vc=1;
echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="10" align="center"><b>Server</b></td>
      </tr>
      <tr align="center">
        <td>'.$server_lang['vorteil'].'</td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>xDE</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>SDE</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>RDE</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>DE:DV</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>NDE</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>QDE</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>BGDE</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>ENSDE</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>NSE</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>SSE</td>';
      echo '</tr>
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil1'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>';

      echo '<tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil2'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
    echo '</tr>';

	echo '
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil3'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>      
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil4'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil5'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil6'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil7'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>      
      <tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil8'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>-</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>-</td>';
      echo '</tr>';
      if($ums_cooperation==0)
      {
      echo '<tr align="center">
        <td>'.$vc++.'. <img id="patt'.($vc-1).'" src="img/i1.gif" border="0" rel="tooltip" title="'.$premium_lang['pavorteil9'].'"></td>';
        if(in_array($ums_cooperation, $serverdata[0][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[1][10]))echo '<td>'.$okgrafik.'</td>';
        if(in_array($ums_cooperation, $serverdata[2][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[3][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[4][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[5][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[6][10]))echo '<td>'.$okgrafik.'</td>';
        //if(in_array($ums_cooperation, $serverdata[7][10]))echo '<td>'.$okgrafik.'</td>';
      echo '</tr>';
      }
echo '</table>';

echo '</div>';
?>

