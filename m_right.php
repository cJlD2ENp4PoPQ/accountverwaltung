<?php
include_once 'content/de/lang/'.$_SESSION[ums_language].'_m_right.lang.php';

$vc=1;

if($ums_user_id>0){
	
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	//auf der "Spielen"-Seite die letzten x News anzeigen
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	if($_REQUEST["command"]=="server"){
	  	echo '<div style="font-weight: bold; margin-bottom: 2px;">Die letzten News:</div>';
		//feststellen in welcher sprache die news angezeigt werden sollen
		$sql = "SELECT newslang FROM ls_user WHERE user_id='$ums_user_id';";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$shownewslang=$row["newslang"];

		if($shownewslang==0) //default, nur die news in der eigenen sprache anzeigen
		{
		  $sqllang='WHERE language='.intval($ums_language);
		}
		else //alle sprachen anzeigen
		{
		  $sqllang='';
		}

		$limit=3;
		
		$sql="SELECT *, SUBSTRING_INDEX(`nachricht`, '.', 3) AS `einleitung` FROM ls_news $sqllang ORDER BY time DESC Limit 0,$limit";
		$sel_news=mysql_query($sql, $db);
		$anz = mysql_num_rows($sel_news);
		while($rew=mysql_fetch_array($sel_news)){
			//datum formatieren
			$news_lang["dateformat"]="d.m.Y";
			$time=date($news_lang["dateformat"], $rew["time"]);
			//überprüfen
			echo '<span class="datecontainer1">'.$time.'</span><h3 style="margin-top: 0px;"><a href="index.php?command=news&newsid='.$rew['id'].'">'.$rew['betreff'].'</a></h3>';
		}
	}
	
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	//den eigenen platz anzeigen
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	if($ums_cooperation==0)
	{
	  $db_daten=mysql_query("SELECT tlplatz FROM ls_user WHERE user_id='$ums_user_id'",$db);
	  $row = mysql_fetch_array($db_daten);

	  echo $m_right_lang['deinplatz'].': '.number_format($row["tlplatz"], 0,"",".").'<br>';
	}

	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	//geworbene spieler anzeigen
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	if($ums_cooperation==0){
	  $result = mysql_query("SELECT COUNT(user_id) AS geworben FROM ls_user WHERE werberid='$_SESSION[ums_user_id]'", $db);
	  $row = mysql_fetch_array($result);
	  $geworben=$row["geworben"];

	  echo $m_right_lang['spielergeworben'].': '.$geworben.'<br><br>';
	}

	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	//alle aktiven spieler erhalten punkte und nur diese werden dargestellt
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	if($ums_cooperation==0)
	{
	echo '<div align="center"><b>'.$m_right_lang['top10'].' '.$m_right_lang['punkte'].'</b> <img id="mrtt'.($vc++).'" style="vertical-align: middle;" src="img/i1.gif" border="0" title="'.$m_right_lang['top10'].' '.$m_right_lang['punkte'].'§'.$m_right_lang['punktedesc'].'"><br>';

	$db_daten=mysql_query("SELECT spielername, tlscore FROM ls_user WHERE tlscore>0 ORDER BY tlplatz ASC LIMIT 10",$db);

	echo '<table width="100%">';

	$platz=1;
	while($row = mysql_fetch_array($db_daten)) 
	{
	  echo '<tr align="center">';

	  echo '<td>'.number_format($platz, 0,"",".").'</td>';
	  echo '<td>'.$row["spielername"].'</td>';
	  //echo '<td>'.number_format($row["tlscore"], 0,"",".").'</td>';
	  echo '</tr>';
	  $platz++;
	}
	echo '</table>';
	echo '</div>';
	}

	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	//  geworbene spieler nach besonderen kriterien darstellen
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	if($ums_cooperation==0)
	{
	  echo '<br><div align="center"><b>'.$m_right_lang['top10'].' '.$m_right_lang['werber'].'</b> <img  id="mrtt'.($vc++).'"style="vertical-align: middle;" src="img/i1.gif" border="0" title="'.$m_right_lang['top10'].' '.$m_right_lang['werber'].'§'.$m_right_lang['werberdesc'].'"><br>';

	  $time=time();
	  $datumregistration=date("Y-m-d",$time-(30*24*3600));
	  $datumletzterlogin=date("Y-m-d",$time-(7*24*3600));

	  //account muß älter als 30 tage sein und innerhalb der letzten 7 tage verwendet worden sein
	  $db_daten = mysql_query("SELECT COUNT(*) AS anzahl, werberid FROM ls_user WHERE register < '$datumregistration' AND last_login>'$datumletzterlogin' AND werberid>0 GROUP BY werberid ORDER BY anzahl DESC, werberid ASC LIMIT 10",$db);

	  echo '<table width="100%">';

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
		//echo '<td>'.number_format($row["tlscore"], 0,"",".").'</td>';
		echo '</tr>';
		$platz++;
	  }
	  echo '</table>';
	  echo '</div>';
	}
}

echo '<script language="javascript">
for(i=1;i<='.($vc-1).';i++)
$("#mrtt"+i).tooltip({ 
      track: true, 
      delay: 0, 
      showURL: false, 
      showBody: "§",
      extraClass: "design1", 
      fixPNG: true, 
      opacity: 0.95
	  });
</script>';

?>