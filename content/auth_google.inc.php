<?php
$content='';

$create_new_account=false;

$content.='<div class="default-container">';

if(empty($_SESSION['google_id']) || empty($_SESSION['google_email']) || empty($_SESSION['google_access_token'])){
	if(isset($_GET["code"])){
		//Include Google Client Library for PHP autoload file
		require_once 'lib/google-api-php-client/vendor/autoload.php';

		//Make object of Google API Client for call Google API
		$google_client = new Google_Client();

		//Set the OAuth 2.0 Client ID
		$google_client->setClientId($GLOBALS['env_login_google_id']);

		//Set the OAuth 2.0 Client Secret key
		$google_client->setClientSecret($GLOBALS['env_login_google_secret']);

		$google_client->setRedirectUri('https://' . $_SERVER['SERVER_NAME'].'/auth_google/');

		//It will Attempt to exchange a code for an valid authentication token.
		$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		
		//This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
		if(!isset($token['error'])){
			//Set the access token used for requests
			$google_client->setAccessToken($token['access_token']);
			
			//Store "access_token" value in $_SESSION variable for future use.
			//$_SESSION['access_token'] = $token['access_token'];
			
			//Create Object of Google Service OAuth 2 class
			$google_service = new Google_Service_Oauth2($google_client);
			
			//Get user profile data from google
			$data = $google_service->userinfo->get();
			//$content.=print_r($data);
			
			$_SESSION['google_id']=$data->getId();
			$_SESSION['google_email']=$data->getEmail();
			$_SESSION['google_access_token']=$token['access_token'];
			$_SESSION['google_name']=$data->getName();

			//$create_new_account=true;
			
		}else{
			$content.='Fehler: '.$token['error'].': '.print_r($token, true);
			$content.='</div>';
			return $content;
		}
	}
}

//gibt es die Google-ID in der DB?
if(!empty($_SESSION['google_id'])){
	$db_daten=mysqli_query($GLOBALS['dbi'],"SELECT * FROM ls_user WHERE google_id='".$_SESSION['google_id']."';");
	$num = mysqli_num_rows($db_daten);
	if($num==1){//login ok
		$user_row = mysqli_fetch_array($db_daten);
		//user einloggen
		session_regenerate_id(true);

		$_SESSION['ums_user_id']=$user_row["user_id"];
		$_SESSION['ums_spielername']=$user_row["spielername"];
		$_SESSION['ums_logins']=$user_row["logins"];			

		//Accountdaten aktualisieren
		$ip=getenv("REMOTE_ADDR");
		$parts=explode(".",$ip);
		$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];

		mysqli_query($GLOBALS['dbi'],"UPDATE ls_user SET google_access_token='".$_SESSION['google_access_token']."', last_login=NOW(), logins=logins+1, last_ip='$ip' WHERE google_id='".$_SESSION['google_id']."';");

		//cookie anlegen						
		$content.='
		<script>
			var date = new Date();
			date.setTime(date.getTime()+(5*365*24*60*60*1000));
			document.cookie="cuser='.$user_row['loginname'].';path=/;expires="+date.toGMTString();
			document.cookie="cpass='.md5($user_row['pass']).';path=/;expires="+date.toGMTString();
		</script>';						

		//redirect old url
		$content.='
		<script>
		window.location.href = "/";
		</script>
		';


	}elseif(!empty($_SESSION['google_email'])){
			//es gibt noch keinen Account, also über die E-Mail-Adresse prüfen
			$db_daten=mysqli_query($GLOBALS['dbi'],"SELECT * FROM ls_user WHERE reg_mail='".$_SESSION['google_email']."';");
			$num = mysqli_num_rows($db_daten);
			if($num==1){//login ok
				//es gibt einen Account, diesen mit FB verknüpfen, dazu die fb_id und das fb_access_token hinterlegen
				//$content.='DEBUG: account found per email, login in user, redirect to old url';
				$user_row = mysqli_fetch_array($db_daten);

				session_regenerate_id(true);

				$_SESSION['ums_user_id']=$user_row["user_id"];
				$_SESSION['ums_spielername']=$user_row["spielername"];
				$_SESSION['ums_logins']=$user_row["logins"];			
	
				//Accountdaten aktualisieren
				$ip=getenv("REMOTE_ADDR");
				$parts=explode(".",$ip);
				$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];
	
				mysqli_query($GLOBALS['dbi'],"UPDATE ls_user SET google_id='".$_SESSION['google_id']."', google_access_token='".$_SESSION['google_access_token']."', last_login=NOW(), logins=logins+1, last_ip='$ip' WHERE reg_mail='".$_SESSION['google_email']."';");

				//cookie anlegen						
				$content.='
				<script>
					var date = new Date();
					date.setTime(date.getTime()+(5*365*24*60*60*1000));
					document.cookie="cuser='.$user_row['loginname'].';path=/;expires="+date.toGMTString();
					document.cookie="cpass='.md5($user_row['pass']).';path=/;expires="+date.toGMTString();
				</script>';						
	
				//redirect old url
				$content.='
				<script>
				window.location.href = "/";
				</script>
				';

		}else{
			//es gibt noch keinen Account, also einen Account anlegen
			$create_new_account=true;
		}		
	}else{
		//es gibt keine E-Mail-Adresse, der Account kann nicht angelegt werden

		$content.='Es ist ein Fehler (NM01) aufgetreten, bitte den Loginvorgang erneut starten.';
		
		$content.='<a class="ok-button" href="/">erneut versuchen</a>';
		
		$content.='</div>';
		return $content;
	}
}

if($create_new_account || isset($_POST['create_account'])){
	$hide_input_area=false;
	if(isset($_POST['create_account'])){
		if(!empty($_SESSION['google_id']) && !empty($_SESSION['google_email']) && !empty($_SESSION['google_name']) && !empty($_SESSION['google_access_token'])){
			if(isset($_POST['reg_agb']) && $_POST['reg_agb']==1){
				
				//Spielername checken
				$spielername_ok=false;
				if(isset($_POST['spielername']) && $_POST['spielername']){
					$spielername=$_POST['spielername'];
					if(!preg_match ("/^[[:alpha:]0-9äöü_=-]*$/i", $spielername)){
						$spielername_error='Der Spielername muss angegeben werden und er darf keine Sonderzeichen enthalten (Ausnahmen sind nur: _-=).';
					}else{
						$db_daten=mysql_query("SELECT user_id FROM ls_user where spielername='$spielername'",$db);
						$vorhanden = mysql_num_rows($db_daten);
						if ($vorhanden>0){
							$spielername_error='Dieser Spielername ist bereits vergeben.';
						}else{
							$spielername_ok=true;
						}
					}
				}else{
					$spielername_error='Der Spielername muss angegeben werden und er darf keine Sonderzeichen enthalten (Ausnahmen sind nur: _-=).';
				}

				if($spielername_ok){
					$werberid=$_SESSION['werber_id'];
					$ip=getenv("REMOTE_ADDR");
					$parts=explode(".",$ip);
					$ip=$parts[0].'.x.'.$parts[2].'.'.$parts[3];
		
					$email1=$_SESSION['google_email'];
					$parts=explode(' ', $_SESSION['google_name'], 2);
					$vorname=$parts[0];
					$nachname=$parts[1];
					$newsletter_accept=intval($_POST['newsletter_reg']);

					//neues pw generieren
					$pwstring='abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
					$newpass=$pwstring[rand(0, strlen($pwstring)-1)];
					for($i=1; $i<=6; $i++) $newpass.=$pwstring[rand(0, strlen($pwstring)-1)];

					//daten in der db ablegen
					$sql="INSERT INTO ls_user
					(loginname, reg_mail, pass, logins
					register, last_login, acc_status,
					last_ip, credits, spielername,
					vorname, nachname, plz,
					ort, strasse, land,
					telefon, tag, monat,
					jahr, geschlecht, werberid, newsletter_accept, google_id, google_access_token)
					VALUES
					('$email1', '$email1', MD5('$newpass'), 1,
					NOW(), NOW(), 1,
					'$ip', 0, '$spielername',
					'$vorname', '$nachname', '$plz',
					'$ort', '$strasse', '$land',
					'$telefon', '$tag', '$monat',
					'$jahr', '$geschlecht', '$werberid', '$newsletter_accept',
					'".$_SESSION['google_id']."', '".$_SESSION['google_access_token']."')";

					//die($sql);

					mysqli_query($GLOBALS['dbi'], $sql);
					//echo("Error description: " . $mysqli -> error);

					$user_id=mysqli_insert_id($GLOBALS['dbi']);
					
					//pa für x tage hinterlgen
					$patime=time()+7*24*3600;
					mysqli_query($GLOBALS['dbi'], "UPDATE ls_user SET patime='$patime' WHERE user_id='$user_id'");

					session_regenerate_id(true);

					$_SESSION['ums_user_id']=$user_id;
					$_SESSION['ums_spielername']=$spielername;
					$_SESSION['ums_logins']=0;
		
					//cookie anlegen						
					$content.='
					<script>
						var date = new Date();
						date.setTime(date.getTime()+(5*365*24*60*60*1000));
						document.cookie="cuser='.$email1.';path=/;expires="+date.toGMTString();
						document.cookie="cpass='.md5($newpass).';path=/;expires="+date.toGMTString();
					</script>';						
		
					//redirect old url
					$content.='
					<script>
					window.location.href = "/";
					</script>
					';

					$hide_input_area=true;

				}else{
					$errormsg.='<div>'.$spielername_error.'</div>';
				}
			}else{
				$errormsg.='<div>Die Nutzungsbedingungen wurden nicht akzeptiert.</div>';
			}
		}else{
			$errormsg='Es liegen nicht alle ben&ouml;tigten Daten vor.';
			print_r($_SESSION);
		}
	}

	if(!$hide_input_area){
		//$content.='DEBUG: account not found, create it';
	
		$content.='<div id="register">';


		$content.='<form method="post" action="/auth_google/" enctype="multipart/form-data">';					
		//$content.='<input type="hidden" name="page" value="register">';
		//$content.='<input type="hidden" name="cmd" value="registeruser">';

		$content.='


		<div>Spielername:</div>
		<div class="mt5"></div>
		<div>
			<input type="text" name="spielername" size="30" maxlength="20" value="'.$_POST['spielername'].'">
		<div>

		<div class="checkbox mt15">
			<input id="reg_newsletter" name="reg_newsletter" value="1" type="checkbox">					
			<label for="reg_newsletter">
				<span></span>
				Ich m&ouml;chte den Newsletter erhalten
			</label>
		</div>

		<div class="checkbox mt15">
			<input id="reg_agb" name="reg_agb" value="1" type="checkbox">
			<label for="reg_agb">
				<span></span>
				Ich stimme den <a href="/nutzungsbedingungen/" target="_blank">Nutzungsbedingungen</a> und den <a href="/datenschutz/" target="_blank">Datenschutzbedingungen</a> zu
			</label>
		</div>
		';

		//ggf. Fehlermeldung ausgeben
		if(!empty($errormsg)){
			$content.='<div class="error mt15">'.$errormsg.'</div>';
		}

		$content.='	<div class="mt15"><input class="btn1" type="submit" name="create_account" value="absenden"></div>';

		$content.='</form>';
		$content.='</div>';
	}
}


$content.='</div>';

echo $content;

?>