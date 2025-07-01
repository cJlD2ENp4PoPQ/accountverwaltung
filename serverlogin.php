<?php
session_start();
include "inc/header.inc.php";

//die sprache des users feststellen
if($_SESSION["ums_language"]==''){
  //auslesen
  $langarray = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
  $lang = str_replace("-","_",$langarray[0]);

  //wenn der browser nichts liefert deutsch nehmen
  if($lang==''){
    $lang='DE';
  }
 
  //in grossbuchstaben umwandeln
  $lang = strtoupper($lang);
  $_SESSION["ums_language"]=1;
  //schauen ob es eine andere sprache ist
  if(strstr($lang,'EN')!='')$_SESSION["ums_language"]=2;
  $ums_language=$_SESSION["ums_language"];
}

//serverdaten einbinden
include "inc/serverdata.inc.php";
include "functions.php";
include 'content/de/lang/'.$_SESSION['ums_language'].'_serverlogin.lang.php';

//ip adresse feststellen
$ip=getenv("REMOTE_ADDR");
$parts=explode(".",$ip);
$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];


//schauen ob es der deb-link ist
$eb=isset($_REQUEST["eb"]) ? intval($_REQUEST["eb"]) : '';

//schauen ob es der bg launcher ist
$fastlogin=isset($_REQUEST["fastlogin"]) ? intval($_REQUEST["fastlogin"]) : 0;
$servertag=$_REQUEST["servertag"] ?? '';

//zuerst über rpc einen loginschlüssel generieren und auf diesen dann per url weiterleiten

//zielserver
$target=intval($_REQUEST["server"]);

//evtl. zielserver über servertag bestimmen
if($fastlogin>0){
	//server nach tag durchsuchen
	for ($i=0;$i<=$sindex;$i++)  {
		if(strtoupper($serverdata[$i][0])==strtoupper($servertag))		{
			$target=$i;
			break;	
		}
	}
}

//das passwort verschlüsselt aus der db auslesen und übertragen
$db_daten=mysql_query("SELECT pass FROM ls_user WHERE user_id='".intval($_SESSION['ums_user_id'])."';",$db);
$row = mysql_fetch_array($db_daten);
$pass='&pass='.$row["pass"];
$accountverwaltung_passwort=$row["pass"];

//überprüfen ob er die mobile version nutzen möchte
if($_SESSION['ums_mobi']==1){
  $mobi='&mobi=1';
}else{
  $mobi='';
}

//wenn der login über den deb-link/bg launcher erfolgt, dann kommt noch ein ip-check dazu
/*
if($eb!='' OR $fastlogin!=''){
	$debip='&ip='.$ip;
}else{
	$debip='';
}
*/

$debip='';

//accountdaten vom server
//if($_SESSION['ums_user_id']==1){
  $databaseKey = $serverdata[$target]['databaseKey'] ?? '';

	$db_temp = mysqli_connect($GLOBALS['env_databaseKey'][$databaseKey]['host'], $GLOBALS['env_databaseKey'][$databaseKey]['user'], $GLOBALS['env_databaseKey'][$databaseKey]['password']);
  mysqli_select_db ($db_temp, $GLOBALS['env_databaseKey'][$databaseKey]['database']);

	//das aktuelle Passwort setzen und einen Loginkey vergeben

	//Loginkey erzeugen
    $pwstring='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $loginkey=$pwstring[rand(0, strlen($pwstring)-1)];
    for($i=1; $i<16; $i++){
		$loginkey.=$pwstring[rand(0, strlen($pwstring)-1)];
	}
  $result=$loginkey;
  
  if($serverdata[$target][8]!=5){
    //DE
    mysqli_query($db_temp, "UPDATE de_login SET loginkey='$loginkey', loginkeytime=UNIX_TIMESTAMP( ), loginkeyip='$ip', pass='$accountverwaltung_passwort' WHERE owner_id = '".intval($_SESSION['ums_user_id'])."';");
  }else{
    //Andalur
    mysqli_query($db_temp, "UPDATE db_user_data SET loginkey='$loginkey', loginkeytime=UNIX_TIMESTAMP( ), loginkeyip='$ip' WHERE owner_id = '".intval($_SESSION['ums_user_id'])."';");

  }

$url="https://".$serverdata[$target][5].$serverdata[$target][6].'index.php?loginkey='.$result.$mobi;

if($result!='error' AND $eb==''){
  //echo $result;
  header("Location: ".$url);
  exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<?php
include "cssinclude.php";
?>
<title><?php echo $serverlogin_lang['title'];?></title>
</head>
<body>
<div id="hauptbody">
<?php
if($result=='error'){
	echo '<br><div align="center">'.$serverlogin_lang['msg_1'].'</div>';
	echo '<br>Dies kann daran liegen, dass man nicht eingeloggt ist. Ggf. liegt es auch an Browsererweiterungen wie z.B. Ghostery. Deaktiviere diese bitte testweise um herauszufinden ob diese das Problem verursachen.';
}else{
  //url zum externen login ausgeben
  echo '<div align="center">';
  echo '<br><b>'.$serverlogin_lang['msg_2'].'</b><br><br><a href="'.$url.'">'.$url.'</a>';
  echo '<br><br>'.$serverlogin_lang['msg_3'].': '.$ip.'<br><br>';
  echo '</div>';

}

//print_r($_SESSION);

?>
</div>
</body>
</html>