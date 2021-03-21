<?php 
include 'content/de/lang/'.$_SESSION['ums_language'].'_forum.lang.php';

$forum_db 		= "deforum_de";
$tablebegin		= "bb1_";
if($ums_language==1)$forum_link = 'http://forum.bgam.es/';
else $forum_link = 'http://forumen.bgam.es/';

$forumdb = @mysql_connect($GLOBALS['env_db_forum_host'],$GLOBALS['env_db_forum_user'],$GLOBALS['env_db_forum_password'], true) or die("Keine Verbindung zur Datenbank m&ouml;glich.");
mysql_select_db($forum_db, $forumdb);

$tablename 		= $tablebegin."users";

// erstmal schauen ob bereits ein Forenaccount mit dem HA verbunden ist.
//Pr�fen ob in der Datenbank schon eine foren_user_id hinterlegt (wenn 0 dann nein)

$result = mysql_query("SELECT * FROM `ls_user` WHERE `user_id` = $_SESSION[ums_user_id]",$db);
$accrow = mysql_fetch_assoc($result);

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
// verarbeitung daten / account erstellen
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
if ($_REQUEST['do'] == 1 AND $accrow['forum_user_id'] == 0) 
{
  //�berpr�fen, ob ein name angegeben worden ist
  $userforennick = trim($_REQUEST['userforennick']);
  
  if($userforennick!='')
  {
    //passwort und e-mail-adresse für die hinterlegung im forum auslesen
	$result 		= mysql_query("SELECT pass, reg_mail FROM `ls_user` WHERE `user_id` = $_SESSION[ums_user_id]",$db); 
	$row 			= mysql_fetch_row($result);
	
	$userforenpass 	= $row[0];
	$usermail 		= $row[1];
	$ip 			= getenv("REMOTE_ADDR");
	
	// check ob der nick noch im forum frei ist
	$result2 		= mysql_query("SELECT count(*) FROM `$forum_db`.`$tablename` WHERE `username` = '$userforennick'",$forumdb);
	
	$row2			= mysql_fetch_row($result2);
	if ($row2[0] >= 1) 
	{
		$errmsgcreate=$forum_lang['errorcreate1'];
	} 
	else 
	{
	  // account im  forum anlegen
	  mysql_query("INSERT INTO `$forum_db`.`$tablename` (`username`, `password`, `sha1_password`, `email`, `userposts`, `groupcombinationid`, 
	`rankid`, `title`, `regdate`, `lastvisit`, `lastactivity`, `usertext`, `signature`, `disablesignature`, `icq`, `aim`, `yim`, `msn`, `homepage`, 
	`birthday`, `avatarid`, `gender`, `showemail`, `admincanemail`, `usercanemail`, `invisible`, `usecookies`, `styleid`, `langid`, `activation`, 
	`blocked`, `daysprune`, `timezoneoffset`, `startweek`, `dateformat`, `timeformat`, `emailnotify`, `notificationperpm`, `buddylist`, `ignorelist`, 
	`receivepm`, `emailonpm`, `pmpopup`, `umaxposts`, `showsignatures`, `showavatars`, `showimages`, `ratingcount`, `ratingpoints`, `threadview`, 
	`useuseraccess`, `isgroupleader`, `rankgroupid`, `useronlinegroupid`, `allowsigsmilies`, `allowsightml`, `allowsigbbcode`, `allowsigimages`, 
	`emailonapplication`, `acpmode`, `acppersonalmenu`, `acpmenumarkfirst`, `acpmenuhidelast`, `usewysiwyg`, `pmtotalcount`, `pminboxcount`, 
	`pmnewcount`, `pmunreadcount`, `reg_ipaddress`, `loginkey`, `maxlogintime`) VALUES ('$userforennick', '$userforenpass', '', '$usermail', 0,
	 2, 4, '', UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ), '', '', 0, '0', '', '', '', '', '0000-00-00', 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 
	 0, '1', 1, 'd.m.Y', 'H:i', 0, 0, '', '', 1, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 4, 4, 1, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '$ip', '', '')"
	,$forumdb); 
	
	  //abfrage welche userid der nutzer im forum erhalten hat
	  $forum_user_id=mysql_insert_id();

	  $tablename2 		= $tablebegin."userfields";
	  $tablename3			= $tablebegin."stats";
	
	  //bl�de tabelle deren sinn ich nicht so ganz verstehe, aber der datensatz mu� da rein
	  mysql_query("INSERT INTO `$forum_db`.`$tablename2` (`userid`) VALUES ('$forum_user_id')",$forumdb);
	
	  //dieser datensatz ist nur f�r "Unser neuestes Mitglied hei�t:"
	  mysql_query("UPDATE `$forum_db`.`$tablename3` SET `lastuserid` = '$forum_user_id'",$forumdb);
	
      //userid und nick in der accountverwaltung einbinden
	  mysql_query("UPDATE `ls_user` SET `forum_user_id` = '$forum_user_id', `forum_nick` = '$forum_nick' WHERE `user_id` = $_SESSION[ums_user_id]",$db); 
	
	  $accrow['forum_user_id']=$forum_user_id;
	}
  }
}

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
// verarbeitung daten / account verknuepfen
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

if ($_REQUEST['do'] == 2 AND $accrow['forum_user_id'] == 0) 
{
  $forennick = trim($_REQUEST['forennick']);
  $forenpass = $_REQUEST['pass'];

  if($forennick!='' AND $forenpass!='')
  {
  	// erstmal testen ob es den nick im forum gibt
	$result = mysql_query("SELECT * FROM `$forum_db`.`$tablename` WHERE `username` = '$forennick'",$forumdb);
	$num = mysql_num_rows($result);
	if ($num == 0) 
	{
	  $errmsgconnect=$forum_lang['errorconnect1'];
	}
	else 
	{
	  // daten des angegebenen Benutzernamens aus der forendb ziehen
	  $result = mysql_query("SELECT `userid` , `username` , `password` FROM `$forum_db`.`$tablename` WHERE `username` = '$forennick'",$forumdb); 
	  $forumrow = mysql_fetch_assoc($result);
		
	  // check ob forenaccount von anderem account schon eingebunden
	  $result2 = mysql_query("SELECT * FROM `ls_user` WHERE `forum_user_id` = '$forumrow[userid]'",$db);
	  $num2 = mysql_num_rows($result2);
	  if ($num2 != 0) 
	  {
	   	$errmsgconnect=$forum_lang['errorconnect2'];
	  }
	  else 
	  {
	    //eingaben checken und datensatz in der verwaltung ablegen
		if (($forennick == $forumrow['username']) && (md5($forenpass) == $forumrow['password'])) 
		{
		  mysql_query("UPDATE `ls_user` SET `forum_user_id` = $forumrow[userid], `forum_nick` = '$forumrow[username]' 
		  WHERE `user_id` = $_SESSION[ums_user_id]",$db);
		  $accrow['forum_user_id']=$forumrow[userid];
		}
		else 
		{
		  $errmsgconnect=$forum_lang['errorconnect3'];
		}
	  }
    }
  }
}

//�berpr�fen ob man einen forenaccount hat, falls nicht loginlink generieren, ansonsten anlagem�glichkeit anbieten
if ($accrow['forum_user_id'] == 0)
{
  echo $forum_lang['nochkeinaccount'].'<br><br>';

  echo '<b>'.$forum_lang['accountanlegen'].':</b><br><br>';
  
  echo
	'<form action="index.php?command=forum&do=1" method="post">';
  if($errmsgcreate!='')echo '<font color="#FF0000">'.$errmsgcreate.'</font><br><br>';
  echo '<input name="userforennick" type="text" maxlength="20"  value="'.$_REQUEST['userforennick'].'"> '.$forum_lang['wunschnick'].'<br><br>
		<input type="submit" name="formaction" value="'.$forum_lang['accountjetztanlegen'].'">
 </form>';
  
  echo '<br><hr style="width: 95%; height: 1px; border: 0px; background-color: #ed7a0a;"><br><b>'.$forum_lang['einenbestehendenaccounteinbinden'].':</b><br><br>';
  
  echo '
	<form action="index.php?command=forum&do=2" method="post">';
  
  if($errmsgconnect!='')echo '<font color="#FF0000">'.$errmsgconnect.'</font><br><br>';
  
  echo '<input name="forennick" type="text" maxlength="20" value="'.$_REQUEST['forennick'].'"> '.$forum_lang['forumbenutzername'].'<br><br>
		<input name="pass" type="password" maxlength="32" value="'.$_REQUEST['pass'].'"> '.$forum_lang['forumpasswort'].'<br><br>
    	<input type="submit" name="formaction" value="'.$forum_lang['accountjetztverbinden'].'">
	</form>';
}
else 
{
  //key generieren
  $pwstring='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $loginkey=$pwstring[rand(0, strlen($pwstring)-1)];
  for($i=1; $i<16; $i++) $loginkey.=$pwstring[rand(0, strlen($pwstring)-1)];

  // key und g�ltigkeit auf 5 min setzen, und da user im forum sein pw �ndern kann, pw aus der verwaltung �bgeben
  mysql_query("UPDATE `$forum_db`.`$tablename` SET `loginkey` = '$loginkey', `maxlogintime` = UNIX_TIMESTAMP( ) + 300, 
  `password` = '$accrow[pass]' , email= '$accrow[reg_mail]' WHERE `userid` = $accrow[forum_user_id]",$forumdb);

  if($_REQUEST['fastlogin']==1)
  {
    header("Location: ".$forum_link."login.php?loginkey=".$loginkey);
  }
  else 
  {
    echo $forum_lang['loginlink'].':<br><br>';
    echo '<div style="text-align: center; width: 100%;"><a href="'.$forum_link.'login.php?loginkey='.$loginkey.'" target="_blank"><b>'.$forum_lang['oeffneforum'].'</b></a></div><br>';

    // da dummerweise der admin des forums den nick des users �ndern kann und ich aber in der datenbank f�r den support gerne den aktuellen haben m�chte
    $result2 = mysql_query("SELECT `username` FROM `$forum_db`.`$tablename` WHERE `userid` = $accrow[forum_user_id]",$forumdb);
     $row2 = mysql_fetch_row($result2);
    mysql_query("UPDATE `ls_user` SET `forum_nick` = '$row2[0]' WHERE `user_id` = $_SESSION[ums_user_id]",$db);
  }
}
?>