<?php
//sprachdatei laden
include 'content/de/lang/'.$ums_language.'_header.lang.php';
include 'content/de/lang/'.$ums_language.'_m_main.lang.php';

//echo "<link href='http://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>";

//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//obere Infoleiste anzeigen
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//l�nderauswahl zusammenbauen
/*
$langstr ='<a href="index.php?setlang=1"><img src="img/de.gif" alt="'.$m_main_lang['deutsch'].'" title="'.$m_main_lang['deutsch'].'"></a>';
$langstr.='&nbsp;<a href="index.php?setlang=2"><img src="img/uk.gif" alt="'.$m_main_lang['englisch'].'" title="'.$m_main_lang['englisch'].'"></a>';
*/
$langstr='';

//spielerdaten auslesen und variablen vorbelegen
if(isset($_SESSION['ums_user_id'])){
	$db_daten=mysql_query("SELECT credits, patime FROM ls_user WHERE user_id='".$_SESSION['ums_user_id']."'",$db);
	$spielerdaten = mysql_fetch_array($db_daten);
	$credits=$spielerdaten['credits'];
	$patime=$spielerdaten['patime'];
}else{
	$credits=0;
	$patime=0;
}

//pa-string
if($patime<time()){
	$pastring='<span class="textred">'.$header_lang['nein'].'</span>';
}else{
	$pastring=date($header_lang["dateformat"], $patime);
}

if(isset($_SESSION['ums_user_id']) && $_SESSION['ums_user_id']>0){
	$db_daten_uc=mysql_query("SELECT anzahl FROM ls_user_count ORDER BY datum DESC LIMIT 1;",$db);
	$row_uc = mysql_fetch_array($db_daten_uc);
	$anzahl_uc=isset($row_uc["anzahl"]) ? $row_uc["anzahl"] : '?';

	echo '
	<div id="top">
		<div id="top_info">
			<div id="top_content">
				<img src="img/credits.png" style="padding-bottom: 3px;" alt="Credits" />&nbsp; <span id="ibcredits">'.number_format($credits, 0,"",".").'</span>
				&nbsp; &nbsp; Spieler: '.$anzahl_uc.'
				
			</div>';


			//Premium: '.$pastring.'
	/*
			<div id="top_lang">
				<a href="index.php?setlang=1"><img src="img/de.png" alt="'.$m_main_lang['deutsch'].'" title="'.$m_main_lang['deutsch'].'" /></a>
				<a href="index.php?setlang=2"><img src="img/en.png" alt="'.$m_main_lang['englisch'].'" title="'.$m_main_lang['englisch'].'" /></a>
			</div>
	*/
	echo '
			<div id="fbr"></div>
		</div>
	</div>';
}else{
	echo '
	<div id="top">
		<div id="top_info">
			<div id="top_content">
			</div>';
	/*
			<div id="top_lang">
				<a href="index.php?setlang=1"><img src="img/de.png" alt="'.$m_main_lang['deutsch'].'" title="'.$m_main_lang['deutsch'].'" /></a>
				<a href="index.php?setlang=2"><img src="img/en.png" alt="'.$m_main_lang['englisch'].'" title="'.$m_main_lang['englisch'].'" /></a>
			</div>
	*/
	echo '
			<div id="fbr"></div>
		</div>
	</div>';
}

/*
echo '<div id="header">';
echo $header_lang['accountverwaltung'];
  echo '<span style="position: absolute; top: 0px; right: 4px;">'.$langstr.'</span>';
		
echo '</div>';
*/

?>