<?php
include_once "inc/header.inc.php";
//////////////////////////////////////////////////////////////
// Werber-ID in die Session packen
//////////////////////////////////////////////////////////////
if(!empty($_REQUEST['a'])){
	$_SESSION['werber_id']=intval($_REQUEST['a']);
}

//////////////////////////////////////////////////////////////
// Login per Cookie
//////////////////////////////////////////////////////////////
if((!isset($_SESSION['ums_user_id']) || $_SESSION['ums_user_id']<1) && isset($_COOKIE['cpass']) && isset($_COOKIE["cuser"])){
	$sql = "SELECT * FROM ls_user WHERE (loginname = '".$_COOKIE["cuser"]."' OR reg_mail = '".$_COOKIE["cuser"]."') AND pass = '".$_COOKIE['cpass']."';";
   
   	//DEBUG
   	//echo $sql;
 
   	$result = mysql_query($sql) OR die(mysql_error());
   	$num = mysql_num_rows($result);
 
   	//wenn ein datensatz gefunden wurde, dann einloggen
   	if($num==1){
		$row = mysql_fetch_array($result);
		$ums_status=$row["acc_status"];
		if($ums_status==1){ //alles richtig eingegen, spieler einloggen
			session_regenerate_id(true);
			$_SESSION['ums_user_id']=$row["user_id"];
			$ums_user_id=$_SESSION['ums_user_id'];
			$_SESSION['ums_spielername']=$row["spielername"];
			$_SESSION['ums_logins']=$row["logins"];
			$_SESSION['ums_cooperation']=$row["cooperation"];
			//$_SESSION["ums_bp_userid"]=$row["bp_userid"];
			//$_SESSION['ums_bp_affiliateID']=$row[bp_affiliateID];
		
		//schauen ob die grafikpacks deaktiviert werden sollen
		if($_COOKIE["cnogp"]=='off')$_SESSION['ums_nogp']=1;else $_SESSION['ums_nogp']=0;
	
		//schauen ob man die mobilversion gewählt hat
		if($_COOKIE["cmobi"]=='off' || $_REQUEST["mobi"]=='off'){
			$_SESSION['ums_mobi']=1;
		}else{
			$_SESSION['ums_mobi']=0;
		}
		
		//ip-adresse speichern
		$ip=getenv("REMOTE_ADDR");
		$parts=explode(".",$ip);
		$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

		mysql_query("UPDATE ls_user SET last_login=NOW(), last_ip='$ip' WHERE user_id='$_SESSION[ums_user_id]'");

		}
   }
}	

//======================== BPUSER ============================================================================================================================
/*
function de_keyValid($userid, $tempkey, $bp_cooperation){
	$valid = false;
	$timestamp = time();
	$timestamp = $timestamp - 600;
	$result = mysql_query("SELECT * FROM ls_user WHERE user_id='$userid' AND cooperation='$bp_cooperation' AND acc_status='1' AND loginkey = '$tempkey' AND loginkeytime > $timestamp");
	if ($result)
	{
		$numrows = mysql_num_rows($result);
		if ($numrows == 1)
		{
			$valid = true;
    		//spielername in die session packen
    		$row = mysql_fetch_array($result);
    		$_SESSION['ums_spielername']=$row["spielername"];
    		$_SESSION['ums_user_id']=$row["user_id"];
    		$_SESSION['ums_bp_affiliateID']=$row[bp_affiliateID];
    		//sprache festlegen
    		if($row[bp_userlang]=='de')
    		$_SESSION['ums_language']=1;
    		else $_SESSION['ums_language']=2;
		}
	}
	return($valid);
}
$params = $_REQUEST["params"];
$params_array = explode("-", $params);
$bp_userid = $params_array[0];
$bp_tempkey = $params_array[1];
$bp_cooperation = $params_array[2];

if (count($params_array) == 3 && de_keyValid($bp_userid, $bp_tempkey, $bp_cooperation))
{
	$bpuser = true;
	$_SESSION["ums_bp_userid"] = $bp_userid;
	$_SESSION['ums_cooperation'] = "$bp_cooperation";
	$ums_cooperation=$bp_cooperation;
	
	//logins hochzählen und ip merken
	$ip=getenv("REMOTE_ADDR");
	$parts=explode(".",$ip);
	$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

    mysql_query("UPDATE ls_user SET logins=logins+1, last_login=NOW(), last_ip='$ip' WHERE user_id='$_SESSION[ums_user_id]'");
}
*/
//=============================================================================================================================================================

//die sprache des users feststellen
/*
if($_SESSION["ums_language"]==''){
	//auslesen
	$langarray = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	$lang = str_replace("-","_",$langarray[0]);

	//wenn der browser nichts liefert deutsch nehmen
	if($lang=='')$lang='DE';

	//in grossbuchstaben umwandeln
	$lang = strtoupper($lang);
	$_SESSION["ums_language"]=1;
	//schauen ob es eine andere sprache ist
	if(strstr($lang,'EN')!='')$_SESSION["ums_language"]=2;
	if(strstr($lang,'US')!='')$_SESSION["ums_language"]=2;
}
//sprache umstellen
if($_REQUEST["setlang"])
{
  $setlang=intval($_REQUEST["setlang"]);
  if($setlang<1 OR $setlang>2)$setlang=1;
  $_SESSION["ums_language"]=$setlang;
}
*/
//12.01.2016, die Sprache ist jetzt immer deutsch
$_SESSION['ums_language']=1;
$ums_language=$_SESSION["ums_language"];

//serverdaten einbinden
include "inc/serverdata.inc.php";
include "functions.php";
include "content/de/lang/1_index.lang.php";

//Zugangsdaten im Cookie speichern
$time=time()+32000000;
if(isset($_REQUEST["loginname"]) && $_REQUEST["loginname"]!=''){
	setcookie("cuser", $_REQUEST["loginname"] , $time);
}
//passwort nur neu setzen, wenn es sich geändert hat
if(isset($_REQUEST["pass"]) && $_REQUEST["pass"]!=''){
	setcookie("cpass", md5($_REQUEST["pass"]) , $time);
}

//cookie löschen, falls häkchen nicht mehr gesetzt ist
/*
if(!isset($_REQUEST["savezg"]) AND (isset($_POST["loginname"]) OR isset($_POST["pass"])))
{
  //wenn es gel�scht wird, dann trotzdem noch nen letzten login erm�glichen
  $_POST["pass"]=$_COOKIE["pass"];
  $time=0;
  setcookie("cuser", "" , $time);
  setcookie("cpass", "" , $time);
}
*/

//cookie für grafikpack
if(isset($_REQUEST["nogp"])){
  $time=time()+32000000;
  setcookie("cnogp", 1 , $time);
}

if(!isset($_REQUEST["nogp"]) AND (isset($_POST["loginname"]) OR isset($_POST["pass"]))){
  setcookie("cnogp", "", 0);
}

//cookie für mobilversion
if(isset($_REQUEST["mobi"])){
  $time=time()+32000000;
  setcookie("cmobi", 1 , $time);
}

if(!isset($_REQUEST["mobi"]) AND (isset($_POST["loginname"]) OR isset($_POST["pass"]))){
	setcookie("cmobi", "", 0);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<?php
include "cssinclude.php";
?>
<title><?php echo $index_lang['title']?></title>

<link rel="manifest" href="/manifest.json">
<script type="text/javascript">
if ("serviceWorker" in navigator) {
	navigator.serviceWorker
		.register("./service-worker.js")
		.then(function() { console.log("Service Worker Registered"); });
}
</script>

<link rel="apple-touch-icon" sizes="76x76" href="/img/icon-76x76.png" />
<link rel="apple-touch-icon" sizes="120x120" href="/img/icon-120x120.png" />
<link rel="apple-touch-icon" sizes="152x152" href="/img/icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="/img/icon-180x180.png" />
<link rel="icon" sizes="192x192" href="/img/icon-192x192.png">

<meta name="viewport" content="width=device-width, initial-scale=1">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META Name="keywords" Content="<?php echo $index_lang['keywords']?>">
<META Name="description" Content="<?php echo $index_lang['description']?>">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="max-age=86400"> 
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/ls.js"></script>
</head>
<body>
<?php


/*
echo session_id().'<br>';
print $_COOKIE['PHPSESSID'];

if($ums_cooperation==2 OR $ums_cooperation==3 OR $ums_cooperation==4 OR $ums_cooperation==5 OR $ums_cooperation==6) //kwick canvas
{
?>
<script type="text/javascript"
  src="http://api.kwick.de/2.0/canvas.js"></script>
<script type="text/javascript">
  var canvasUrl = 'http://www.kwick.de/apps/canvas/576/',
  scCanvas = new SC.Canvas(canvasUrl);
</script>
<?php
}
*/

include "header.php";

//menü einbinden
include "m_main.php";

echo '<div id="content_bg">';

echo '<div id="content">';

//Untermenü ausgeben
if(!empty($um)){
	echo $um.'<br><br>';
}

//man ist eingeloggt:
if(isset($_SESSION["ums_user_id"]) && $_SESSION["ums_user_id"]>0){
	//last_login updaten
	mysql_query("UPDATE ls_user SET last_login=NOW() WHERE user_id='".$_SESSION['ums_user_id']."';");	
	
	//zuerst überprüfen ob ein spielername gesetzt ist
	if($_SESSION['ums_spielername']==''){
		include "content/changenick.inc.php";
		$_REQUEST["command"]='none';
	}
	
	if(isset($_REQUEST["servertag"]) && $_REQUEST["servertag"]!='Accountverwaltung'){
		if($_REQUEST["servertag"]=='Forum') header("Location: index.php?command=forum&fastlogin=1");
		else header("Location: serverlogin.php?fastlogin=1&servertag=".$_REQUEST["servertag"]);
	}
  
	//wenn nichts ausgew�hlt ist wird die serverseite angezeigt
	//if($_REQUEST["command"]=="" AND ($ums_cooperation==1 OR $ums_cooperation==2 OR $ums_cooperation==3 OR $ums_cooperation==4 OR $ums_cooperation==5 OR $ums_cooperation==6 OR $ums_cooperation==7))
	//$_REQUEST["command"]='server';
	
	
	if($_REQUEST["command"]=="logout"){
			//beim Logout Cookies löschen
			setcookie("cuser", '' , $time);
			setcookie("cpass", '' , $time);
			
			session_destroy();
			session_start();
			header("Location: index.php");
	}
	elseif($_REQUEST["command"]=="news"){
		include "content/news.inc.php";
	}elseif($_REQUEST["command"]=="server"){
		include "content/server.inc.php";
	}
	elseif($_REQUEST["command"]=="server_direct"){
		include "content/server_direct.inc.php";
	}	
	elseif($_REQUEST["command"]=="createaccount"){
		include "content/createaccount.inc.php";
	}
	elseif($_REQUEST["command"]=="account"){
		include "content/account.inc.php";
	}
	elseif($_REQUEST["command"]=="credittransfer"){
		include "content/credittransfer.inc.php";
	}
	elseif($_REQUEST["command"]=="getplaytime"){
		include "content/getplaytime.inc.php";
	}
	elseif($_REQUEST["command"]=="premium"){
		include "content/premium.inc.php";
	}
	/*
	elseif($_REQUEST["command"]=="tickets")
	{
		include "content/tickets.inc.php";
	}*/
	elseif($_REQUEST["command"]=="linkaccount"){
		include "content/linkaccount.inc.php";
	}
	elseif($_REQUEST["command"]=="community"){
		include "content/community.inc.php";
	}
	elseif($_REQUEST["command"]=="communitytl"){
		include "content/communitytl.inc.php";
	}
	elseif($_REQUEST["command"]=="forum"){
		include "content/forum.inc.php";
	}
	elseif($_REQUEST["command"]=="support" && isset($GLOBALS['env_enable_support_page']) && $GLOBALS['env_enable_support_page']==1){
		include "content/support.inc.php";
	}
	elseif($_REQUEST["command"]=="de_kb"){
		include "content/de_kb.inc.php";
	}  
}else{	//man ist nicht eingeloggt
	$urlparts=explode('/',$_SERVER["REQUEST_URI"]);
	$page=$urlparts[1];
	//remove parameter
	$url_parameter=explode('?',$page);
	$page=$url_parameter[0];

	//wenn nichts ausgewählt ist wird die loginseite angezeigt
	/*
	if($_REQUEST["command"]==""){
		$_REQUEST["command"]='login';
	}
	*/

	if(isset($_REQUEST["command"]) && $_REQUEST["command"]=="logout"){
		//include "content/register.inc.php";
		session_destroy();
		header("Location: index.php?command=login");
	}
	elseif(isset($_REQUEST["command"]) && $_REQUEST["command"]=="register"){
		include "content/register.inc.php";
	}
	elseif(isset($_REQUEST["command"]) && $_REQUEST["command"]=="registered"){
		include "content/registered.inc.php";
		include "content/login.inc.php";
	}
	elseif(isset($_REQUEST["command"]) && $_REQUEST["command"]=="login"){
		include "content/login.inc.php";
	}elseif(isset($_REQUEST["command"]) && $_REQUEST["command"]=="pwsend"){
		include "content/pwsend.inc.php";
	}elseif($page=="auth_fb"){
		include "content/auth_fb.inc.php";
	}elseif($page=="auth_google"){
		include "content/auth_google.inc.php";
	}else{
		//Standardseite ist Login
		include "content/login.inc.php";
	}
}

//div contentright end
echo '</div>';

//footer
include "footer.php";
?>
</body>
</html>

<?php
/*
//test-session-variable definieren 
if(!isset($_SESSION['usergecheckt']))$_SESSION['usergecheckt']=0;

//wenn testvariable 0, dann user checken
if($_SESSION['usergecheckt']==0)
{
  if ($_SERVER['HTTP_X_FORWARDED_FOR']
   || $_SERVER['HTTP_X_FORWARDED']
   || $_SERVER['HTTP_FORWARDED_FOR']
   || $_SERVER['HTTP_VIA']
   || in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
   || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30))
  {
	exit('Proxy Erkannt');
  }
  //wenn der check keinen proxy ergeben hat, dann testvariable auf 1 setzen, so ist der check w�hrend der sitzung nicht mehr n�tig
  $_SESSION['usergecheckt']=1;
}
*/
?>