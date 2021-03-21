<?php
include 'content/de/lang/'.$ums_language.'_communitytl.lang.php';
include_once 'content/de/lang/'.$_SESSION[ums_language].'_m_right.lang.php';
$vc=1;

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
// spielerrangliste
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////

echo '<br><b>'.$communitytl_lang['punkterangliste'].' <img id="ctltt'.($vc++).'" style="vertical-align: middle;" src="img/i1.gif" border="0" title="'.$m_right_lang['top10'].' '.$m_right_lang['punkte'].'ß'.$m_right_lang['punktedesc'].'"><br>';

//alle aktiven spieler erhalten punkte und nur diese werden dargestellt
$db_daten=mysql_query("SELECT spielername, tlscore FROM ls_user WHERE tlscore>0 ORDER BY tlplatz ASC LIMIT 1000",$db);

//tabellenanfang

echo '<table width="100%">';
echo '<tr align="center"><td><b>'.$communitytl_lang['platz'].'</b></td><td><b>'.$communitytl_lang['spielername'].'</b></td><td><b>'.$communitytl_lang['punkte'].'</b></td></tr>';

$platz=1;
while($row = mysql_fetch_array($db_daten)) 
{
  echo '<tr align="center">';
  
  echo '<td>'.number_format($platz, 0,"",".").'</td>';
  echo '<td>'.$row["spielername"].'</td>';
  echo '<td>'.number_format($row["tlscore"], 0,"",".").'</td>';
  echo '</tr>';
  $platz++;
}

echo '</table>';


/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//  geworbene spieler nach besonderen kriterien darstellen
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
if($ums_cooperation==0)
{
  echo '<br><b>'.$communitytl_lang['werberrangliste'].' <img id="ctltt'.($vc++).'" style="vertical-align: middle;" src="img/i1.gif" border="0" title="'.$m_right_lang['top10'].' '.$m_right_lang['werber'].'ß'.$m_right_lang['werberdesc'].'"><br>';

  $time=time();
  $datumregistration=date("Y-m-d",$time-(30*24*3600));
  $datumletzterlogin=date("Y-m-d",$time-(7*24*3600));

  //account muﬂ ‰lter als 30 tage sein und innerhalb der letzten 7 tage verwendet worden sein
  $db_daten = mysql_query("SELECT COUNT(*) AS anzahl, werberid FROM ls_user WHERE register < '$datumregistration' AND last_login>'$datumletzterlogin' AND werberid>0 GROUP BY werberid ORDER BY anzahl DESC, werberid ASC",$db);

  echo '<table width="100%">';
  echo '<tr align="center"><td><b>'.$communitytl_lang['platz'].'</b></td><td><b>'.$communitytl_lang['spielername'].'</b></td><td><b>'.$communitytl_lang['geworben'].'</b></td></tr>';

  $platz=1;
  while($row = mysql_fetch_array($db_daten)) 
  {
    $werberid=$row["werberid"];
  
    echo '<tr align="center">';
  
    echo '<td>'.number_format($platz, 0,"",".").'</td>';
  
    //spielername anhand der werberid auslesen
    $result2 = mysql_query("SELECT spielername FROM ls_user WHERE user_id='$werberid'",$db);
    $row2 = mysql_fetch_array($result2);
    $werbername=$row2["spielername"];
    if($werbername=='')$werbername='-';
  
    echo '<td>'.$werbername.'</td>';
    echo '<td>'.number_format($row["anzahl"], 0,"",".").'</td>';
    echo '</tr>';
    $platz++;
  }
  echo '</table>';
}

echo '<script language="javascript">
for(i=1;i<='.($vc-1).';i++)
$("#ctltt"+i).tooltip({ 
      track: true, 
      delay: 0, 
      showURL: false, 
      showBody: "ß",
      extraClass: "design1", 
      fixPNG: true, 
      opacity: 0.95
	  });
</script>';

?>