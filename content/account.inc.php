<?php
include 'content/de/lang/'.$ums_language.'_account.lang.php'; 

$ums_user_id=$_SESSION['ums_user_id'];
$errmsg='';

echo '<div style="width: 100%;">';

//account löschen
if(isset($_POST['delpass']) || isset($_POST['delcheck1']) || isset($_POST['delcheck2']) || isset($_POST['delbutton'])){
	$delpass=SecureValue($_POST[delpass]);
	
	$db_daten=mysql_query("SELECT user_id FROM ls_user WHERE user_id = '$ums_user_id' AND pass=MD5('$delpass')");
	$num = mysql_num_rows($db_daten);
	if ($num==1){ //das passwort ist korrekt
		
		//löschen
		if ($_POST['delcheck1']=="1" and $_POST['delcheck2']=="1"){
			//überprüfen, ob er noch einen aktiven account hat
			$anzacc=0;
			for ($i=0;$i<=$sindex;$i++){
				$result=doPost($serverdata[$i][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&isaccount=1&id='.$ums_user_id, $serverdata[$i][5]);
				$anzacc=$anzacc+$result;
			}
			//wenn er keinen aktiven spielaccount hat, dann den hauptaccount löschen
			if($anzacc==0){
				//account löschen
				if($ums_user_id!=1){
					mysql_query("DELETE FROM ls_user WHERE user_id = '$ums_user_id'");
					mysql_query("DELETE FROM ls_credits WHERE user_id = '$ums_user_id'");
					mysql_query("DELETE FROM ls_psc_transactions WHERE user_id = '$ums_user_id'");
				}
				
				session_destroy();
				header("Location: index.php");
			}else{
				$errmsg.='<font color="FF0000">'.$account_lang['msg_1'].'</font>';
			}

		}else{
			$errmsg.='<font color="FF0000">'.$account_lang['msg_2'].'</font>';
		}
	}else{
		$errmsg.='<font color="FF0000">'.$account_lang['msg_3'].'</font>';
	}
}

//neues passwort setzen
if(isset($_POST['oldpass']) || isset($_POST['newpass']) || isset($_POST['pass1']) || isset($_POST['pass2'])){
	$oldpass=SecureValue($_POST['oldpass']);
	$pass1=SecureValue($_POST['pass1']);
	$pass2=SecureValue($_POST['pass2']);
		
	//echo '<br>'.HTTP_REFERER.'<br>';
	$db_daten=mysql_query("SELECT * FROM ls_user WHERE user_id = '$ums_user_id'", $db);
	$row = mysql_fetch_array($db_daten);

	$passwordOK=false;
	if(password_verify(trim($oldpass), $row['pass'])){
		$passwordOK=true;
	}

	if ($passwordOK){ //oldpass ist korrekt
		$pass1=trim($pass1);
		$pass2=trim($pass2);
		if ($pass1==$pass2){
			$minpwchars=6;
			if (strlen($pass1)>$minpwchars-1){
				$pass1_crypt=password_hash($pass1, PASSWORD_DEFAULT);
				mysql_query("UPDATE ls_user set pass = '$pass1_crypt', newpass='' WHERE user_id = '$ums_user_id'",$db);
				$errmsg.='<font color="00FF00">'.$account_lang['msg_7'].'</font>';
			}else{
				$errmsg.='<font color="FF0000">'.$account_lang['msg_4'].': '.$minpwchars.').</font>';
			}
		}else{
			$errmsg.='<font color="FF0000">'.$account_lang['msg_5'].'</font>';
		}
	}else{
		$errmsg.='<font color="FF0000">'.$account_lang['msg_6'].'</font>';
	}
}

//newssprache ändern
if(isset($_REQUEST["changenewslang"])){
	//schauen wie der wert aktuell ist
	$db_daten=mysql_query("SELECT newslang FROM ls_user WHERE user_id='$ums_user_id'",$db);
	$row = mysql_fetch_array($db_daten);
	$newslang=$row["newslang"];
	if($newslang==0){
		mysql_query("UPDATE ls_user SET newslang=1 WHERE user_id = '$ums_user_id'",$db);	
	}else{
		mysql_query("UPDATE ls_user SET newslang=0 WHERE user_id = '$ums_user_id'",$db);	
	}
}

//externer browserlink anzeigen
if(isset($_REQUEST["changeeblink"])){
	//schauen wie der wert aktuell ist
	$db_daten=mysql_query("SELECT showeblink FROM ls_user WHERE user_id='$ums_user_id'");
	$row = mysql_fetch_array($db_daten);
	$newslang=$row["showeblink"];
	if($newslang==0){
		mysql_query("UPDATE ls_user SET showeblink=1 WHERE user_id = '$ums_user_id'",$db);	
	}else{
		mysql_query("UPDATE ls_user SET showeblink=0 WHERE user_id = '$ums_user_id'",$db);	
	}
}


if($errmsg!='')echo $errmsg;
//daten auslesen
$sql = "SELECT * FROM ls_user WHERE user_id='$ums_user_id';";
$result = mysql_query($sql) OR die(mysql_error());
$row = mysql_fetch_array($result);

//�berpr�fen ob ein launcherkey gesetzt ist, bzw. vergeben werden soll

if($row['launcherkey']=='' || (isset($_REQUEST['setnewlauncherkey']) && $_REQUEST['setnewlauncherkey']==1) )
{
  $pwstring='abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789';
  $newlaucherkey=$pwstring[rand(0, strlen($pwstring)-1)];
  for($i=1; $i<=15; $i++) $newlaucherkey.=$pwstring[rand(0, strlen($pwstring)-1)];

  mysql_query("UPDATE ls_user SET launcherkey='$newlaucherkey' WHERE user_id = '$ums_user_id'",$db);
  $row['launcherkey']=$newlaucherkey;
}

echo '<form action="index.php?command=account" method="POST">';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$account_lang['accountdaten'].'</b></td>
      </tr>
      <tr>
        <td width="50%">'.$account_lang['spielername'].':</td>
        <td width="50%">'.$row["spielername"].'</td>
      </tr>
      <tr>
        <td>'.$account_lang['accountid'].':</td>
        <td>ID'.$ums_user_id.'</td>
      </tr>
      <tr>
        <td>E-Mail:</td>
        <td>'.$row['reg_mail'].'</td>
      </tr>      
      <tr>
        <td>'.$account_lang['credits'].':</td>
        <td>'.$row["credits"].'</td>
      </tr>';


/*
echo' <tr>
        <td>Registrierungsdatum:</td>
        <td>'.$row["register"].'</td>
      </tr>';

echo' <tr>
        <td>Werbelink:</td>
        <td>http://login.die-ewigen.com/?a='.$ums_user_id.'</td>
      </tr>';
*/
echo '</table><br>';

//einstellungen
//sprachstring
if($row["newslang"]==0)$langstr=$account_lang['newsanzeigeaktuellesprache']; else $langstr=$account_lang['newsanzeigeallesprachen'];
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$account_lang['einstellungen'].'</b></td>
      </tr>';
      /*
      <tr>
        <td width="300">'.$account_lang['newsanzeige'].':</td>
        <td width="350"><a href="index.php?command=account&changenewslang=1">'.$langstr.'</a></td>
      </tr>';
    */
//externer browser link
if($row["showeblink"]==0)$langstr=$account_lang['nichtanzeigen']; else $langstr=$account_lang['anzeigen'];
echo '<tr>
        <td>'.$account_lang['externerbrowserlink'].':</td>
        <td><a href="index.php?command=account&changeeblink=1">'.$langstr.'</a></td>
      </tr>';
echo '</table>';

echo '</form>';


echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>BGAM.ES Launcher</b></td>
      </tr>
      <tr>
        <td width="300">Loginkey:</td>
        <td width="350"><a href="index.php?command=account&setnewlauncherkey=1">'.$row['launcherkey'].'</a></td>
      </tr>
      <tr>
        <td>Download:</td>
        <td><a href="download/bglauncher.exe">BGAM.ES Launcher</a></td>
      </tr>';
echo '</table>';

echo '<form action="index.php?command=account" method="POST">';

echo '<table border="0" cellpadding="3" cellspacing="0">';
echo '<tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center"><b>'.$account_lang['passwortaendern'].'</b></td>
      </tr>
      <tr>
        <td width="300">'.$account_lang['altespasswort'].':</td>
        <td width="350"><input type="password" name="oldpass" value=""></td>
      </tr>
      <tr>
        <td>'.$account_lang['neuespasswort'].':</td>
        <td><input type="password" name="pass1" value=""></td>
      </tr>
      <tr>
        <td>'.$account_lang['neuespasswortwiederholen'].':</td>
        <td><input type="password" name="pass2" value=""></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="Submit" name="newpass" value="'.$account_lang['passwortaendern'].'"></td>
      </tr>
      ';

echo '</table>';

echo '</form>';
echo '<form action="index.php?command=account" method="POST">';

echo '<table border="0" cellpadding="3" cellspacing="0">';
echo '<tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center"><b>'.$account_lang['accountloeschen'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$account_lang['msg_8'].'</td>
      </tr>

      <tr>
        <td width="50%">'.$account_lang['passwort'].':</td>
        <td width="50%"><input type="password" name="delpass" value=""></td>
      </tr>
      <tr>
        <td><input name="delcheck1" type="checkbox" value="1">'.$account_lang['bestaetigung'].' 1</td>
        <td><input name="delcheck2" type="checkbox" value="1">'.$account_lang['bestaetigung'].' 2</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="Submit" name="delbutton" value="'.$account_lang['accountloeschen'].'"></td>
      </tr>
      ';

echo '</table>';
echo '</form></div>';


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
//freundschaftswerbung
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//freundschaftswerbung
echo '<br>';
rahmen_oben($account_lang['freundschaftswerbung']);
//werbelink bauen
$directory=str_replace("/index.php","/",$_SERVER["PHP_SELF"]);
$directory=$_SERVER["PHP_SELF"];
$server=$_SERVER["SERVER_NAME"].$directory;
$wlink="https://".$server."?command=register&a=".$_SESSION["ums_user_id"];  

//geworbene user auslesen
$result = mysql_query("SELECT COUNT(user_id) AS geworben FROM ls_user WHERE werberid='$_SESSION[ums_user_id]'");
$row = mysql_fetch_array($result);
$geworben=$row["geworben"];
  
//daten ausgeben
echo '<table width="100%">';
echo '<tr><td class="rahmen0" align="left">
'.$account_lang['werbelink'].': '.$wlink.'<br>

<br>
'.$acount_lang['geworben'].': '.$geworben.'

</td></tr>';

echo '</table>';
rahmen_unten();

function rahmen_oben($text)
{
  echo '<table border="0" rahmen0padding="0" rahmen0spacing="0" width="100%;">
        <tr>
          <td align="center" class="rahmen0" style="font-weight: bold;">'.$text.'</td>
        </tr>
        <tr>
        <td>';
}

function rahmen_unten()
{
  echo '</td>
        </tr>
        </table><br>';
}
?>

	<script type="text/javascript">

	$('div, img, a').tooltip({ 
    track: true, 
    delay: 0, 
    showURL: false, 
    showBody: "&",
    extraClass: "design1", 
    fixPNG: true,
    opacity: 0.15,
    left: 0
});

?>