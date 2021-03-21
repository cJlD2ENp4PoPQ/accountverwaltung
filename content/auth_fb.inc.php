<?php
$content='';

$create_new_account=false;

//print_r($_SESSION);

//User hat den Loginvorgang abgebrochen
if($_REQUEST['error']=='access_denied' && $_REQUEST['error_reason']=='user_denied'){

	$content.='
	<div class="default-container grey666 text-center">
		<div class="mb18 font-size20">
			<div class="icon-alert-sad"></div>Der Loginvorgang wurde abgebrochen.
		</div>
	</div>';

	die($content);
}

$content.='<div class="default-container">';

if(!isset($_POST['create_account'])){

	if(empty($_SESSION['fb_id']) || empty($_SESSION['fb_name']) || empty($_SESSION['fb_email']) || empty($_SESSION['fb_access_token'])){
		require_once 'lib/Facebook/autoload.php';

		$fb = new Facebook\Facebook([
			'app_id' => $GLOBALS['env_login_fb_id'], // Replace {app-id} with your app id
			'app_secret' => $GLOBALS['env_login_fb_secret'],
			'default_graph_version' => 'v3.2',
		]);
		
		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$content.='Graph returned an error: ' . $e->getMessage();
			//exit;
			$content.='</div>';
			die($content);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
			$content.='Facebook SDK returned an error: ' . $e->getMessage();
			//exit;
			$content.='</div>';
			die($content);
		}

		if(!isset($accessToken)) {
			if ($helper->getError()) {
				//header('HTTP/1.0 401 Unauthorized');
				$content.="Error: " . $helper->getError() . "\n";
				$content.="Error Code: " . $helper->getErrorCode() . "\n";
				$content.="Error Reason: " . $helper->getErrorReason() . "\n";
				$content.="Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
				//header('HTTP/1.0 400 Bad Request');
				$content.='Bad request';
			}

			$content.='</div>';
			//exit;
			$content.='</div>';
			die($content);
		}

		///////////////////////////////////////////////////////////////////
		// Logged in
		///////////////////////////////////////////////////////////////////

		/*
		$content.='<h3>Access Token</h3>';
		$content.=print_r($accessToken->getValue(),true);
		*/
		
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		
		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		//$content.='<h3>Metadata</h3>';
		//$content.=print_r($tokenMetadata,true);

		/*
		//Test auf E-Mail-Adresse
		$ssopes=$tokenMetadata->getScopes();
		for($s=0;$s<count($scopes);$s++){
			$content.='<br>scope: '.$scopes[$s];
		}
		*/

		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId($GLOBALS['env_login_fb_id']); // Replace {app-id} with your app id
		// If you know the user ID this access token belongs to, you can validate it here
		//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();
		
		if(!$accessToken->isLongLived()){
			// Exchanges a short-lived access token for a long-lived one
			try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				$content.="<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";

				$content.='</div>';
				die($content);
				//exit;
			}
			
			$content.='<h3>Long-lived</h3>';
			$content.=print_r($accessToken->getValue(),true);
		}
		
		$_SESSION['fb_access_token'] = (string) $accessToken;		

		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get('/me?fields=id,name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			$content.='Graph returned an error: ' . $e->getMessage();
			$content.='</div>';
			die($content);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$content.='Facebook SDK returned an error: ' . $e->getMessage();
			$content.='</div>';
			die($content);
		}
		
		$user = $response->getGraphUser();
		//$content.='A: '.print_r($user, true);
		
		//wir brauchen zuerst die FB-ID um zu überprüfen ob es evtl. schon 
		//einen Account damit gibt und man somit direkt einloggen kann
		$fb_id=$user['id'];
		$fb_email=$user['email'];
		$fb_name=$user['name'];

	}else{
		$fb_id=$_SESSION['fb_id'];
		$fb_email=$_SESSION['fb_email'];
		$fb_name=$_SESSION['fb_name'];
	}

	//gibt es die FB-ID in der DB?
	if(!empty($fb_id)){
		$db_daten=mysqli_query($GLOBALS['dbi'],"SELECT * FROM ls_user WHERE fb_id='".$fb_id."';");
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
	
			mysqli_query($GLOBALS['dbi'],"UPDATE ls_user SET fb_access_token='".$_SESSION['fb_access_token']."', last_login=NOW(), logins=logins+1, last_ip='$ip' WHERE fb_id='".$fb_id."';");

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


		}elseif(!empty($fb_email)){
			//es gibt noch keinen Account, also über die E-Mail-Adresse prüfen
			$db_daten=mysqli_query($GLOBALS['dbi'],"SELECT * FROM ls_user WHERE reg_mail='".$fb_email."';");
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
	
				mysqli_query($GLOBALS['dbi'],"UPDATE ls_user SET fb_id='$fb_id', fb_access_token='".$_SESSION['fb_access_token']."', last_login=NOW(), logins=logins+1, last_ip='$ip' WHERE reg_mail='".$fb_email."';");

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
				$_SESSION['fb_id']=$fb_id;
				$_SESSION['fb_email']=$fb_email;
				$_SESSION['fb_name']=$fb_name;
			}		
		}else{
			//es gibt keine E-Mail-Adresse, vermutlich hat der User das Recht entzogen
			//Hinweis an User bzgl. Recht und Erzeugung neuer Link

			$helper = $fb->getRedirectLoginHelper();
			
			$permissions = ['email']; // Optional permissions
			$callbackUrl = htmlspecialchars('https://' . $_SERVER['SERVER_NAME'].'/auth_fb/');
			$loginUrl = $helper->getLoginUrl($callbackUrl, $permissions).'&auth_type=rerequest';

			$content.='
			<div class="mb18 grey666 text-center" style="position: relative; line-height: 20px; padding-right: 200px; padding-top: 16px;">
				<div style="position: absolute; right: 150px; top: 18px;">
					<a class="auth-button" href="'.$loginUrl.'">erneut versuchen</a>
				</div>
	
				<div class="icon-alert-sad" style="margin-bottom: -16px; margin-right: 15px;"></div>
				Um einen Account anzulegen muss die zugehörige E-Mail-Adresse übermittelt werden.<br>Diese dient zum verschicken von Bestell- und Reservierungsbestätigungen.
			</div>
			';					
			
			
			$content.='</div>';
			die($content);
		}
	}
}

if($create_new_account || isset($_POST['create_account'])){
	$hide_input_area=false;
	if(isset($_POST['create_account'])){
		if(!empty($_SESSION['fb_id']) && !empty($_SESSION['fb_name']) && !empty($_SESSION['fb_email']) && !empty($_SESSION['fb_access_token'])){
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
		
					$email1=$_SESSION['fb_email'];
					$parts=explode(' ', $_SESSION['fb_name'], 2);
					$vorname=$parts[0];
					$nachname=$parts[1];
					$newsletter_accept=intval($_POST['newsletter_reg']);

					//neues pw generieren
					$pwstring='abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
					$newpass=$pwstring[rand(0, strlen($pwstring)-1)];
					for($i=1; $i<=6; $i++) $newpass.=$pwstring[rand(0, strlen($pwstring)-1)];

					//daten in der db ablegen
					$sql="INSERT INTO ls_user
					(loginname, reg_mail, pass, logins, 
					register, last_login, acc_status,
					last_ip, credits, spielername,
					vorname, nachname, plz,
					ort, strasse, land,
					telefon, tag, monat,
					jahr, geschlecht, werberid, newsletter_accept, fb_id, fb_access_token)
					VALUES
					('$email1', '$email1', MD5('$newpass'), 1,
					NOW(), NOW(), 1,
					'$ip', 0, '$spielername',
					'$vorname', '$nachname', '$plz',
					'$ort', '$strasse', '$land',
					'$telefon', '$tag', '$monat',
					'$jahr', '$geschlecht', '$werberid', '$newsletter_accept',
					'$fb_id', '$fb_access_token')";

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


		$content.='<form method="post" action="/auth_fb/" enctype="multipart/form-data">';					
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