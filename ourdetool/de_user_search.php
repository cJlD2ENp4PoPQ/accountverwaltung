<?php
include "../inccon.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Suche</title>
<?php include "cssinclude.php";?>
</head>
<body>
<form action="de_user_search.php" method="get">
(+ ID, - Login, * Nick, % Mail, ~ ip,| name, ° ort) (?[-*%~|°] wildcard)
&nbsp;&nbsp;
<input type="text" name="sstr" value="">
<input type="Submit" name="search" value="Suchen">

<?php
//schauen wonach gesucht wird
//+ user_id
//- nic
//* spielername
//% Mail
//~ IP

//| Vor-/Nachname
//° Ort

$sstr=$_REQUEST['sstr'];
  if ($sstr!='')
  switch($sstr[0]){
    case '+': //user_id
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE user_id='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '-': //nic
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE loginname='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '*': //spielername
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE spielername='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '%': //email-adresse
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE reg_mail='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '~': //ip-adresse
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE last_ip='$sstr'",$db);
      $countmultiip = mysql_num_rows($db_daten);
      if($countmultiip>1) echo "<b><font color=\"#FF0000\">Multi</font></b>";
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '|': //Vor-/Nachname
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE vorname='$sstr' or nachname='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
    case '°': //Ort
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE ort like '$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;

    case '?': //wildcard suche
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $sstr = str_replace("%","$",$sstr);
      echo '<script type="text/javascript">'."\r\n".'<!--'."\r\n".'parent.frames["de_user_anzeige"].location.href = "wildcard.php?sstr='.$sstr.'";'."\r\n".'//-->'."\r\n".'</script>'."\r\n";
      $sstr = '';
      break;      
    default: //user_id
      $sstr = str_replace($sstr[0].$sstr[1],$sstr[1],$sstr);
      $db_daten=mysql_query("SELECT user_id FROM ls_user WHERE user_id='$sstr'",$db);
      $row = mysql_fetch_array($db_daten);
      $sstr=$row["user_id"];
      break;
  }//switch sstr ende
  if ($sstr=='')die ('Kein User gefunden.');
  else
{
$sstr=trim($sstr);
echo '&nbsp;&nbsp;User ID: '.$sstr;
echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="info.php?uid='.$sstr.'" target="de_user_anzeige">Info</a>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="de_user_logviewer.php?uid='.$sstr.'" target="de_user_anzeige">Logviewer 2</a>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="de_user_ips.php?uid='.$sstr.'" target="de_user_anzeige">IPs</a>';
//echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="de_user_jsonlog.php?uid='.$sstr.'" target="de_user_anzeige">Logviewer</a>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="de_user_credits.php?uid='.$sstr.'" target="de_user_anzeige">Credits</a>';
echo '</form>';

echo '<script type="text/javascript">'."\r\n".'<!--'."\r\n".'parent.frames["de_user_anzeige"].location.href = "info.php?uid='.$sstr.'";'."\r\n".'//-->'."\r\n".'</script>'."\r\n";
}
?>

</body>
</html>
