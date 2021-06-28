<?php
include 'content/de/lang/'.$ums_language.'_login.lang.php';

echo '<div style="width: 100%; max-width: 650px; margin: auto;">';

function hex2ascii($str){
    //echo $str;
	  $p = '';
    for ($i=0; $i < strlen($str); $i=$i+2)
    {
        $p .= chr(hexdec(substr($str, $i, 2)));
    }
    return $p;
} 



//logindaten überprüfen
if( (isset($_REQUEST["loginname"]) && $_REQUEST["loginname"]!='') || (isset($_REQUEST["pass"]) && $_REQUEST["pass"]!='') || (isset($_REQUEST['launcherkey']) && $_REQUEST['launcherkey']!='') ){


	$_REQUEST["loginname"] = strip_tags($_REQUEST["loginname"]);
    //$_REQUEST["loginname"] = mysql_escape_string($_REQUEST["loginname"]);

	$_REQUEST["pass"] = strip_tags($_REQUEST["pass"]);
    //$_REQUEST["pass"] = mysql_escape_string($_REQUEST["pass"]);

    if(isset($_REQUEST["launcherkey"]))    {
		$_REQUEST["launcherkey"] = strip_tags($_REQUEST["launcherkey"]);
    	//$_REQUEST["launcherkey"] = mysql_escape_string($_REQUEST["launcherkey"]);
    }
	
	//wenn fastlogin gesetzt ist, zugangsdaten entschlüsseln
	if(isset($_REQUEST['launcherkey'])){
		$sql = "SELECT * FROM ls_user WHERE launcherkey='".$_REQUEST['launcherkey']."';";
	}else{


		$sql = "SELECT * FROM ls_user WHERE
		(loginname = '".$_REQUEST['loginname']."' OR reg_mail = '".$_REQUEST['loginname']."') AND (pass = MD5('".$_REQUEST['pass']."') OR newpass = MD5('".$_REQUEST['pass']."') OR 
		pass = '".$_COOKIE['cpass']."' OR pass='".$_REQUEST['pass']."');";
	}
	
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
			$_SESSION['ums_spielername']=$row["spielername"];
			$_SESSION['ums_logins']=$row["logins"];
			//$_SESSION['ums_cooperation']=$row["cooperation"];
			//$_SESSION["ums_bp_userid"]=$row["bp_userid"];
			//$_SESSION['ums_bp_affiliateID']=$row[bp_affiliateID];
			
			//schauen ob die grafikpacks deaktiviert werden sollen
			if($_REQUEST["nogp"]=='off')$_SESSION['ums_nogp']=1;else $_SESSION['ums_nogp']=0;

			//schauen ob man die mobilversion gew�hlt hat
			if($_REQUEST["mobi"]=='off')$_SESSION['ums_mobi']=1;else $_SESSION['ums_mobi']=0;
			
			//if($_REQUEST["mobi"]=='off')@mail('issomad@die-ewigen.com', 'Mobillogin: '.$_SESSION[ums_user_id], '', 'FROM: issomad@die-ewigen.com');

			//testen ob er das alternativ-pw verwendet hat
			$sql = "SELECT user_id FROM ls_user WHERE user_id='$_SESSION[ums_user_id]' AND newpass = MD5('".$_REQUEST['pass']."');";
			$result = mysql_query($sql) OR die(mysql_error());
			$num = mysql_num_rows($result);
			//logins hochzählen und ip-adresse speichern
			$ip=getenv("REMOTE_ADDR");
			$parts=explode(".",$ip);
			$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

			mysql_query("UPDATE ls_user SET logins=logins+1, last_login=NOW(), last_ip='$ip' WHERE user_id='$_SESSION[ums_user_id]'");

			if($num==1)//er hat das alternative pw benutzt
			{
				mysql_query("UPDATE ls_user SET pass=newpass WHERE user_id='$_SESSION[ums_user_id]'");
				mysql_query("UPDATE ls_user set newpass='' WHERE user_id='$_SESSION[ums_user_id]'");
			}
			
			//beim fastlogin über den launcher direkt den entsprechenden server aufrufen, wenn gew�nscht
			if($_REQUEST['launcherkey']!='' AND $_REQUEST["servertag"]!='Accountverwaltung')
			{
				if($_REQUEST["servertag"]=='Forum') header("Location: index.php?command=forum&fastlogin=1");
				else header("Location: serverlogin.php?fastlogin=1&servertag=".$_REQUEST["servertag"]);
				exit;
			}

			header("Location: index.php");
		}
		elseif($ums_status==2) echo $login_lang['msg_1'];
	}
	else echo '<font color="#FF0000">'.$login_lang['msg_2'].'</font>';
}


/////////////////////////////////////////////////////////////////
// Facebook
/////////////////////////////////////////////////////////////////
if(!isset($_GET['code'])){
	$_SESSION['FBRLH_state']=md5(mt_rand(10000000, 99999999));
}

$fb_loginUrl='https://www.facebook.com/v3.2/dialog/oauth?client_id='.$GLOBALS['env_login_fb_id'].'&state='.$_SESSION['FBRLH_state'].'&redirect_uri=https://' . $_SERVER['SERVER_NAME'].'/auth_fb/&scope=email&response_type=code&sdk=php-sdk-5.7.0';

/////////////////////////////////////////////////////////////////
// Google
/////////////////////////////////////////////////////////////////
$google_loginUrl='https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id='.$GLOBALS['env_login_google_id'].'&redirect_uri=https://'.$_SERVER['SERVER_NAME'].'/auth_google/&scope=email profile&approval_prompt=auto&state='.mt_rand(1000000,9999999);


/////////////////////////////////////////////////////////////////
// Registrierung per Dienst
/////////////////////////////////////////////////////////////////
if(!empty($GLOBALS['env_login_fb_id']) || !empty($GLOBALS['env_login_google_id'])){
	echo '
	<div id="register-page">
		<div class="box-left">';
	
	if(!empty($GLOBALS['env_login_fb_id'])){
		echo '<a class="button-auth-facebook" href="'.$fb_loginUrl.'"><div class="icon-login-facebook"></div>Login mit facebook</a>';
	}
	if(!empty($GLOBALS['env_login_fb_id']) && !empty($GLOBALS['env_login_google_id'])){
		echo '<div class="mt15"></div>';
	}
	if(!empty($GLOBALS['env_login_google_id'])){
		echo '<a class="button-auth-google" href="'.$google_loginUrl.'"><div class="icon-login-google"></div>Login mit Google</a>';
	}
	
	echo '</div>';
}



$height='30';

echo '
<div class="box-right">
	<h2>Login per E-Mail</h2>
';
echo '<form action="index.php?command=login" method="post">';
echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-left: auto; margin-right: auto;">';
/*
<tr align="center">
<td height="'.$height.'" colSpan="2" align="center"><b>'.$login_lang['login'].'</b></td>
</tr>
*/

$cuser=isset($_COOKIE["cuser"]) ? $_COOKIE["cuser"] : '';
$cpass=isset($_COOKIE["cpass"]) ? $_COOKIE["cpass"] : ''; 

echo '
<tr align="center">
<td height="'.$height.'" width="180">Loginname oder E-Mail</td>
<td height="'.$height.'" width="180"><input type="text" name="loginname" value="'.$cuser.'" tabindex="1"></td>
</tr>

<tr align="center">
<td height="'.$height.'">'.$login_lang['passwort'].'</td>
<td height="'.$height.'"><input type="password" name="pass" value="'.$cpass.'" tabindex="2"></td>
</tr>
<tr align="center">
<td height="'.$height.'" colSpan="2"><br><input class="btn1" style="text-transform: uppercase;" type="Submit" name="login" value="'.$login_lang['login'].'"></td>
</tr>';

/*
echo '<tr align="center">
<td colspan="2"><input type="Checkbox" name="savezg" value="1"';
if($_COOKIE["cuser"]<>'' OR $_COOKIE["cpass"]<>'') echo 'checked';
echo '>&nbsp;'.$login_lang[zugangsdatenspeichern].'</td>
</tr>';
*/

//mobile Version verwenden
/*
echo '
<tr align="center">
<td colspan="2"><br><br><input type="Checkbox" name="mobi" value="off"';
if($_COOKIE["cmobi"]<>'') echo 'checked';
echo '>&nbsp;'.$login_lang[mobilversion].'</td>
</tr>';
*/

//Grafikpack deaktivieren
/*
echo '
<tr align="center">
<td colspan="2"><br><input type="Checkbox" name="nogp" value="off"';
if($_COOKIE["cnogp"]<>'') echo 'checked';
echo '>&nbsp;'.$login_lang['grafikpack'].'</td>
</tr>';
*/

//Passwort vergessen
echo '
<tr align="center">
<td colspan="2"><br><a href="index.php?command=pwsend">'.$login_lang['pwvergessen'].'</a></td>
</tr>
</table>
			</div>
		</div>
	</form>
</div>';
?>