<?php
set_time_limit(240);
include "../inc/serverdata.inc.php";
include "../inc/env.inc.php";
include "../functions.php";

include "../inccon.php";
?>
<html>
<head>
</head>
<body>
<?php

echo 'Timestamp: '.time();

//der cronjob wird st�ndlich aufgerufen

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
//nachricht per e-mail, wenn es offene supporttickets gibt
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
//8,14,20 uhr
if(strftime("%H")==8 OR strftime("%H")==14 OR strftime("%H")==20){
	$db_daten=mysql_query("SELECT * FROM ls_tickets WHERE status=0  ORDER BY created DESC",$db);
   	$num = mysql_num_rows($db_daten);
   	if($num>0)
   	{
   		echo 'Supportticketreminder versendet.<br>';
   		
		$header  ='MIME-Version: 1.0' . "\r\n";
		$header .='Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$header .="FROM: noreply@die-ewigen.com" . "\r\n";
   		
		$body='<html><head><style>body{font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;font-size: 16px; 
color: #FFFFFF;background-color: #000000;} a {color: #f8ae56; text-align: center;} </style>
</head><body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<h1>Offene Supporttickets</h1>
<table cellspacing="0" cellpadding="0" width="100%" border="0" bgcolor="#000000">
<tr><td width="100%" align="center" style="background-image:url(http://login.bgam.es/img/bg.jpg);">
<table cellspacing="0" cellpadding="0" width="600" border="0" style="background-image:url(http://login.bgam.es/img/bgtr1.png); padding:10px;">
<tr><td align="left">';


   		   		
   		//kopf
  		$body.='<table width="100%">';
   		$body.='<tr><td>Betreff</td><td>User</td><td>erstellt</td><td>letzte &auml;nderung</td><td>Supporter</td></tr>';
    	
   		while($row = mysql_fetch_array($db_daten))
   		{
   			$body.= '<tr>';
    		
   			$body.= '<td>'.$row['thema'].'</a></td>';
   			$body.= '<td>'.$row['user_id'].'</a></td>';
   			$body.= '<td>'.date("G:i:s d.m.Y", $row['created']).'</td>';
   			$body.= '<td>'.date("G:i:s d.m.Y", $row['modified']).'</td>';
   			if($row['supporter']=='')$status='noch keiner';else $status=$row['supporter'];
   			$body.= '<td>'.$status.'</td>';
    		
   			echo '</tr>';
   		}
   	
    	
   		$body.='</table>';
    	$body.='</td></tr></table></td></tr></table></body></html>';
		
		//info per e-mail an die supporter
		require_once '../lib/phpmailer/class.phpmailer.php';
		require_once '../lib/phpmailer/class.smtp.php';
	
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
		$mail->Host = $GLOBALS['env_mail_server'];
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = $GLOBALS['env_mail_user'];
		$mail->Password = $GLOBALS['env_mail_password'];
		$mail->setFrom($GLOBALS['env_mail_noreply'], 'Die Ewigen');
		$mail->addReplyTo($GLOBALS['env_mail_noreply'], 'Die Ewigen');
		$mail->addAddress('supportverteiler@die-ewigen.com');

		$mail->IsHTML(true); 
		$mail->Subject = 'BGAM.ES Supportticket-Reminder';
		$mail->Body = $body;
	
		//send the message, check for errors
		$mail->send();		
		
    	//mail('supportverteiler@die-ewigen.com', "BGAM.ES Supportticket-Reminder", $body, $header);
   	}
}

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
//aktive Spieler in der Accountverwaltung innerhalb der letzten 7 Tage
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// 0 Uhr
if(strftime("%H")==0){
	//aktive user
	$datum=date("Y-m-d".' 00:00:00', time()-3600*24*7);
	$db_daten=mysql_query("SELECT COUNT(*) AS anzahl FROM ls_user WHERE register < '$datum' AND last_login >= '$datum';",$db);
	$row = mysql_fetch_array($db_daten);
	$anzahl=$row['anzahl']-1;
	
	//aktive PAs
	$db_daten=mysql_query("SELECT COUNT(*) AS anzahl FROM ls_user WHERE patime > '".time()."';",$db);
	$row = mysql_fetch_array($db_daten);
	$pa_anz=$row['anzahl'];	

	mysql_query("INSERT INTO `ls_user_count` ( `server` , `datum` , `anzahl` , `pa_anz` )VALUES (99, NOW(), '$anzahl', '$pa_anz');");
	
}

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// nicht genutzte Accounts löschen
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// 1 Uhr
if(strftime("%H")==1){
	//aktive user
	$datum=date("Y-m-d".' 00:00:00', time()-3600*24*7);
	$db_daten=mysql_query("DELETE FROM ls_user WHERE logins=0 AND register < '$datum';",$db);
}


////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
//per rpc von allen servern die punkte des spielers anfordern
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/*
unset($alldata);

for($i=0;$i<=$sindex;$i++)
{
  //echo '<br>Server '.$i.':';
  $result=doPost($serverdata[$i][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&gettlscore=1', $serverdata[$i][5]);
  $teilmenge=explode(';', $result);
  
  //aus den erhaltenen daten die punkte lesen
  for($j=0;$j<count($teilmenge);$j++)
  {
  	$data=explode('@', $teilmenge[$j]);
  	$alldata[$data[0]]+=$data[1];
  }
}

//die daten in die db schreiben
//zuerst alle punkte auf null setzen
mysql_query("UPDATE ls_user SET tlscore=0 WHERE tlscore>0",$db);

foreach($alldata AS $key => $zeile)
{
  //if ($key==1)echo '<br>'.$key.': '.$zeile;
  mysql_query("UPDATE ls_user SET tlscore='$zeile' WHERE user_id='$key'",$db);
}


////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
// pl�tze in der db vorbelegen
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

$db_daten=mysql_query("SELECT user_id FROM ls_user WHERE tlscore>0 ORDER BY tlscore DESC",$db);

$platz=1;
while($row = mysql_fetch_array($db_daten)) 
{
  $uid=$row["user_id"];
  mysql_query("UPDATE ls_user SET tlplatz='$platz' WHERE user_id='$uid'",$db);
  $platz++;
}
*/

?>
</body>
</html>