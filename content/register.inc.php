<?php
include 'content/de/lang/'.$ums_language.'_register.lang.php';
include "inc/links.inc.php";

$fehlermsg='';

echo '<div id="register-page">';

function is_email($email){
	
	//check e-mail for right format
	$pattern =
	"/^[-_.[:alnum:]_]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mobi|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i";
	if(preg_match($pattern, $email)){
		//if format ok, check for domain-blacklist
		//load domain-blacklist
		$blacklist=array();
		$handle = @fopen("inc/mogelmails.csv", "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$blacklist[]=trim($buffer);
			}
			if (!feof($handle)) {
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}
		
		//print_r($blacklist);
		
		$list=explode("@", $email);
		$domainpart=trim($list[1]);
		
		if(in_array($domainpart, $blacklist))return(0);
		else return(1);
	}
	else
	return(0);
}

$spielername=isset($_REQUEST['spielername']) ? $_REQUEST['spielername'] : '';
$email1=isset($_REQUEST['email1']) ? $_REQUEST['email1'] : '';
$vorname=isset($_REQUEST['vorname']) ? $_REQUEST['vorname'] : '';
$nachname=isset($_REQUEST['nachname']) ? $_REQUEST['nachname'] : '';
$agb=isset($_REQUEST['agb']) ? intval($_REQUEST['agb']) : 0;
$newsletter_accept=isset($_REQUEST['newsletter_accept']) ? intval($_REQUEST['newsletter_accept']) : 0;


//Werber-ID
$werberid=isset($_POST["referer"]) ? intval($_POST["referer"]) : 0;
if(isset($_POST["referer"]) && !empty($_POST["referer"])){
	$_SESSION['werber_id']=intval($_POST["referer"]);
}
if(!isset($_SESSION['werber_id'])){
	$_SESSION['werber_id']='';
}


if(isset($_REQUEST['newreg'])){
	
	/*
	$loginname=SecureValue($_REQUEST['loginname']);
	$email2=SecureValue($_REQUEST['email2']);

    $plz=SecureValue($_REQUEST['plz']);
    $ort=SecureValue($_REQUEST['ort']);
    $strasse=SecureValue($_REQUEST['strasse']);
    $land=SecureValue($_REQUEST['land']);
	
	  $telefon=SecureValue($_REQUEST['telefon']);
    
    $tag=SecureValue($_REQUEST['tag']);
    $monat=SecureValue($_REQUEST['monat']);
    $jahr=SecureValue($_REQUEST['jahr']);
    $geschlecht=SecureValue($_REQUEST['geschlecht']);
	*/

	$leeresfeld=0;
	//schauen ob die daten korrekt eingegeben worden sind
	if($spielername!=''){
		if(!preg_match ("/^[[:alpha:]0-9äöü_=-]*$/i", $spielername))$fehlermsg.=$newreg_lang['fehlermsg1'];
		else
		{
		$db_daten=mysql_query("SELECT user_id FROM ls_user where spielername='$spielername'",$db);
		$vorhanden = mysql_num_rows($db_daten);
		if ($vorhanden>0)$fehlermsg.='<br>'.$newreg_lang['fehlermsg2'];
		}
	}
	else $leeresfeld=1;

	/*	
	if($loginname!=''){
		if(!preg_match ("/^[[:alpha:]0-9äöü_=-]*$/", $loginname))$fehlermsg.='<br>'.$newreg_lang['fehlermsg3'];
		else
		{
		$db_daten=mysql_query("SELECT user_id FROM ls_user where loginname='$loginname'",$db);
		$vorhanden = mysql_num_rows($db_daten);
		if ($vorhanden>0)$fehlermsg.='<br>'.$newreg_lang['fehlermsg4'];
		}
	}
	else $leeresfeld=1;
	

	if($loginname!='' AND $spielername!=''){
		if ($loginname==$spielername) $fehlermsg.='<br>'.$newreg_lang['fehlermsg5'];
	}
	*/

	if($email1!=''){
		if (is_email($email1)==0){
			$fehlermsg.='<br>'.$newreg_lang['fehlermsg6'];
		}else{
			//hier noch schauen ob es sie schon gibt
			
			$db_daten=mysql_query("SELECT user_id FROM ls_user where reg_mail='$email1'",$db);
			$vorhanden = mysql_num_rows($db_daten);
			if ($vorhanden>0)$fehlermsg.='<br>'.$newreg_lang['fehlermsg7'];
		}
	}
	else $leeresfeld=1;

	if($vorname!='')
	{
		if(!preg_match ("/^[[:alpha:] ÖÄÜöäüß-]*$/", $vorname))$fehlermsg.='<br>'.$newreg_lang['fehlermsg9'];
	}
	else $leeresfeld=1;

	if($nachname!='')
	{
		if(!preg_match ("/^[[:alpha:] öäüÖÄÜ-]*$/", $nachname))$fehlermsg.='<br>'.$newreg_lang['fehlermsg10'];
	}
	else $leeresfeld=1;

	/*if($strasse!='')
	{*/
		//if(!preg_match ("/^[[:alpha:]0-9 ÖÄÜöäüß\.-]*$/", $strasse))$fehlermsg.='<br>'.$newreg_lang['fehlermsg11'];
	/*}
	else $leeresfeld=1;*/

	/*
	if($plz!='')
	{
		if(!preg_match ("/^[0-9]*$/", $plz))$fehlermsg.='<br>'.$newreg_lang['fehlermsg12'];
	}
	else $leeresfeld=1;

	if($ort!='')
	{
		if(!preg_match ("/^[[:alpha:] öäüÖÄÜ-]*$/", $ort))$fehlermsg.='<br>'.$newreg_lang['fehlermsg13'];
	}
	else $leeresfeld=1;

	if($land==$newreg_lang['bittewaehlen'])$fehlermsg.='<br>'.$newreg_lang['fehlermsg14'];

	if(!preg_match ("/^[0-9 \/\+-]*$/", $telefon))$fehlermsg.='<br>'.$newreg_lang['fehlermsg15'];

	if($tag!=$newreg_lang['tag']){if(!preg_match ("/^[0-9]*$/", $tag))  $fehlermsg.='<br>'.$newreg_lang['fehlermsg16'];}
	if($monat!=$newreg_lang['monat']){if(!preg_match ("/^[0-9]*$/", $monat))$fehlermsg.='<br>'.$newreg_lang['fehlermsg16'];}
	if($jahr!=$newreg_lang['jahr']){if(!preg_match ("/^[0-9]*$/", $jahr)) $fehlermsg.='<br>'.$newreg_lang['fehlermsg16'];}

	if($geschlecht!=1 && $geschlecht!=2)$fehlermsg.='<br>'.$newreg_lang['fehlermsg17'];
	*/

	if($agb!=1)
	{
		$fehlermsg.='<br>'.$newreg_lang['fehlermsg19'];
	}


	if ($leeresfeld==1) $fehlermsg.='<br>'.$newreg_lang['fehlermsg20'];

	if ($fehlermsg==''){
		//hier kommt das einfügen der accountdaten rein
		$ip=getenv("REMOTE_ADDR");
		$parts=explode(".",$ip);
		$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];


		//neues pw generieren
		$pwstring='abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
		$newpass=$pwstring[rand(0, strlen($pwstring)-1)];
		for($i=1; $i<=6; $i++) $newpass.=$pwstring[rand(0, strlen($pwstring)-1)];

		/*
		if($tag==$newreg_lang['tag']) $tag=0;
		if($monat==$newreg_lang['monat']) $monat=0;
		if($jahr==$newreg_lang['jahr']) $jahr=0;
		*/

		//daten in der db ablegen
		$sql="INSERT INTO ls_user
		(loginname, reg_mail, pass,
		register, last_login, acc_status,
		last_ip, credits, spielername,
		vorname, nachname, plz,
		ort, strasse, land,
		telefon, tag, monat,
		jahr, geschlecht, werberid, newsletter_accept)
		VALUES
		('$email1', '$email1', MD5('$newpass'),
		NOW(), NOW(), 1,
		'$ip', 0, '$spielername',
		'$vorname', '$nachname', '$plz',
		'$ort', '$strasse', '$land',
		'$telefon', '$tag', '$monat',
		'$jahr', '$geschlecht', '$werberid', '$newsletter_accept')";
		mysql_query($sql, $db);

		$user_id=mysql_insert_id();
		
		//pa für x tage hinterlgen
		$patime=time()+7*24*3600;
		mysql_query("UPDATE ls_user SET patime='$patime' WHERE user_id='$user_id'", $db);    

		//registrierungs-email versenden
		$accountid = $user_id;
		// Aktivierungs-Link
		$text=$newreg_lang['regmailbody'];
		//Paswort und Login-Name eintragen
		$text=str_replace("{SPIELER}",utf8_decode($spielername),$text);
		$text=str_replace("{LOGIN}",$loginname,$text);
		$text=str_replace("{PASS}",$newpass,$text);

		//mail Senden:
		//@mail($email1, $newreg_lang[regmailbetreff], $text, 'FROM: noreply@die-ewigen.com');
		
		require_once 'lib/phpmailer/class.phpmailer.php';
		require_once 'lib/phpmailer/class.smtp.php';

		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	$mail->smtpConnect([
		'ssl' => [
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		]
	]);  
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = "smtp.1und1.de";
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = "noreply@die-ewigen.com";
		$mail->Password = $GLOBALS['env_mail_password'];
		$mail->setFrom('noreply@die-ewigen.com', 'Die Ewigen');
		$mail->addReplyTo('noreply@die-ewigen.com', 'Die Ewigen');
		$mail->addAddress($email1, utf8_decode($vorname.' '.$nachname));
		$mail->Subject = $newreg_lang['regmailbetreff'];
		$mail->Body = $text;

		//send the message, check for errors
		$mail->send();
		
		header("Location: index.php?command=registered");
		exit;
	}
}

echo '<form action="index.php?command=register" method="post">';

//echo '<h1>'.$newreg_lang['accountregistrierung'].'</h1>';

if(isset($fehlermsg) && $fehlermsg!=''){
	echo '<font color="#CC0000">'.$fehlermsg.'</font><br><br>';
}

//echo '<div class="dflex">';

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
echo '
<div class="box-left">
	<a class="button-auth-facebook" href="'.$fb_loginUrl.'"><div class="icon-login-facebook"></div>Registrieren mit facebook</a>
	<div class="mt15"></div>
	<a class="button-auth-google" href="'.$google_loginUrl.'"><div class="icon-login-google"></div>Registrieren mit Google</a>
</div>';


/////////////////////////////////////////////////////////////////
// Registrierung per Dienst
/////////////////////////////////////////////////////////////////
echo '
<div class="box-right">
	<h2>Registrierung per E-Mail</h2>
';


//Loginname
//echo '<div>'.$newreg_lang['loginname'].' '.$newreg_lang['loginname1'].':</div>';
//echo '<div><input type="text" name="loginname" size="20" maxlength="20" value="'.$loginname.'"></div>';
//Email 1
echo '<div>'.$newreg_lang['email'].':</div>';
echo '<div class="mt5"></div>';
echo '<div><input type="text" name="email1" size="30" maxlength="100" value="'.$email1.'"></div>';
//Email 2
//echo '<div>'.$newreg_lang['email1'].':</div>';
//echo '<div><input type="text" name="email2" size="40" maxlength="100" value="'.$email2.'"></div>';
//Spielername
echo '<div class="mt15"></div>';
echo '<div>'.$newreg_lang['spielername'].':</div>';
echo '<div class="mt5"></div>';
echo '<div><input type="text" name="spielername" size="30" maxlength="20" value="'.$spielername.'"></div>';
//Vorname
echo '<div class="mt15"></div>';
echo '<div>'.$newreg_lang['vorname'].':</div>';
echo '<div class="mt5"></div>';
echo '<div><input type="text" name="vorname" size="30" maxlength="20" value="'.$vorname.'"></div>';
//Nachname
echo '<div class="mt15"></div>';
echo '<div>'.$newreg_lang['nachname'].':</div>';
echo '<div class="mt5"></div>';
echo '<div><input type="text" name="nachname" size="30" maxlength="20" value="'.$nachname.'"></div>';

/*
//Straße
echo '<div>'.$newreg_lang['strasse'].':</div>';
echo '<div><input type="text" name="strasse" size="30" maxlength="30" value="'.$strasse.'"></div>';
//PLZ
echo '<div>'.$newreg_lang['plz'].':</div>';
echo '<div><input type="text" name="plz" size="5" maxlength="5" value="'.$plz.'"></div>';
//Ort
echo '<div>'.$newreg_lang['ort'].':</div>';
echo '<div><input type="text" name="ort" size="30" maxlength="30" value="'.$ort.'"></div>';

//Land
echo '<div>'.$newreg_lang['land'].':</div>';
if ($land=='')$land=$newreg_lang['bittewaehlen'];
echo '<div>
<select name="land">
<option selected>'.$land.'</option>
'.$newreg_lang['laenderliste'].'
</select>
</div>';

//Telefon
echo '<div>'.$newreg_lang['telefonnummer'].' (optional):</div>';
echo '<div><input type="text" name="telefon" size="20" maxlength="20" value="'.$telefon.'"></div>';

//Geburtstdatum
if ($tag=='')$tag=$newreg_lang['tag'];
if ($monat=='')$monat=$newreg_lang['monat'];
if ($jahr=='')$jahr=$newreg_lang['jahr'];
echo '<div>'.$newreg_lang['geburtsdatum'].' (optional):</div>';

echo '<div>
<select name="tag">
<option selected>'.$tag.'</option>';
for ($i=1;$i<=31;$i++){
  echo '<option>'.$i.'</option>';
}
echo '
</select>

<select name="monat">
<option selected>'.$monat.'</option>';
for ($i=1;$i<=12;$i++){
echo '<option>'.$i.'</option>';
}
echo '
</select>

<select name="jahr">
<option selected>'.$jahr.'</option>';
for ($i=1950;$i<=date("Y")-8;$i++){
  echo '<option>'.$i.'</option>';
}
echo '
</select>
</div>
';

//Geschlecht
echo '<div>'.$newreg_lang['geschlecht'].':</div>';
echo '<div>
<select name="geschlecht">
<option value="-1"';
if($geschlecht<1) echo ' selected';
echo '>'.$newreg_lang['auswaehlen'].'</option>
<option value="1"';
if($geschlecht==1) echo ' selected';
echo '>'.$newreg_lang['maennlich'].'</option>
<option value="2"';
if($geschlecht==2) echo ' selected';
echo '>'.$newreg_lang['weiblich'].'</option>
</select>
</div>
';
*/

//Werber-ID
echo '<div class="mt15"></div>';
echo '<div>'.$newreg_lang['werberid'].' (optional):</div>';
echo '<div class="mt5"></div>';
echo '<div><input type="text" name="referer" size="6" maxlength="6" value="'.$_SESSION['werber_id'].'"></div>';

//AGB/Datenschutz
echo '<div class="mt15">
<input type="Checkbox" ';
if($agb=="1") echo "checked";
echo ' name="agb" value="1">'.$newreg_lang['agb1'].' 
	<a href="http://bgam.es/agb.php" target="_blank">'.$newreg_lang['agb2'].'</a> und die <a href="http://bgam.es/datenschutz.php" target="_blank">Datenschutzerkl&auml;rung. Ich bin 16 Jahre oder &auml;lter, bzw. habe die Erlaubnis meiner/meines Erziehungsberechtigten.</a>
</div>
';

//Newsletter
echo '<div class="mt15">
<input type="Checkbox" ';
if(isset($_REQUEST['newsletter_accept']) && $_REQUEST['newsletter_accept']=="1") echo "checked";
echo 'name="newsletter_accept" value="1">'.$newreg_lang['newsletter_accept'].'
</div>';
echo '<div class="mt15"></div>';
echo '		<input class="btn1" type="Submit" name="newreg" value="'.$newreg_lang['registrieren'].'">

			</div>
	
		</form>
	<div style="font-weight: bold; color: #CC0000; margin-top: 20px; width: 100%; text-align: center;">'.$newreg_lang['hinweis'].'</div>
</div>';

?>