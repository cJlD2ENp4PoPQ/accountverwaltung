<?php
session_start();

function fehlermsg($msg){
  echo '<html><head>';
  include "cssinclude.php";
  echo '</head><body>';
  echo $msg;
  echo '</body></html>';
}

//schaue ob man eingeloggt ist
/*
if (!isset($_SESSION['ums_user_id']))
{
  fehlermsg('<br><center><font size="2" color="FF0000">Du bist nicht eingeloggt.<br>Bitte logge dich ein:<br><br><a href="index.php">Login</a>');
  exit;
}*/

//ip-test
/*if ($_SESSION['ums_user_ip']!=$_SERVER['REMOTE_ADDR'])
{
  fehlermsg('<br><center><font size="2" color="FF0000">IP-Fehler, deine IP stimmt nicht mit der Sitzungs-IP �berein.<br>Bitte logge dich neu ein:<br><br><a href="index.php">Login</a>');
  session_destroy();
  exit;
}*/

//schaue ob auch die richtige server-id verwendet wird
/*
if ($ums_servid!=$sv_servid)
{
  fehlermsg('<br><center><font size="2" color="FF0000">Sessionfehler. Die Session geh�rt nicht zu diesem Server.<br>Bitte logge dich neu ein:<br><br><a href="index.php">Login</a>');
  session_destroy();
  exit;
}
*/
//session nach maximal einer zeit X killen
/*
if ((($ums_session_start+$sv_session_lifetime)<time())AND($_SESSION['ums_one_way_bot_protection']==1) AND ($eftachatbotdefensedisable!=1))
{
  echo '<html><head>';
  include "cssinclude.php";

  //dateiname speichern um sp�ter darauf weiterleiten zu k�nnen
  $_SESSION['ums_bot_protection_filename']=$PHP_SELF;
  if($thisisefta!=1)//bereich im de-style ausgeben
  {
    echo '<meta http-equiv="expires" content="0">
</head><body><script src="jssammlung.js" type="text/javascript"></script>
<form action="botcheck.php" method="post" name="loginform">
<div align="center">
<br>
<center>
<br>
<table border="0" cellpadding="0" cellspacing="0">
<tr align="center">
<td width="13" height="37" class="rol">&nbsp;</td>
<td colspan="4" align="center" class="ro">Bot-Schutz-Abfrage</td>
<td width="13" class="ror">&nbsp;</td>
</tr>
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colspan="4"><a href="index.php"><img src="imagegenerator.php" alt="Bild" border="0"></a></td>
<td class="rr">&nbsp;</td>
</tr>
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colspan="4">Zahl: &nbsp;&nbsp;<input type="Text" name="nummer" value="" tabindex="3" onbeforepaste="noPaste()" onpaste="noPaste()"></td>
<td class="rr">&nbsp;</td>
</tr>
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colSpan="4"><input type="Submit" name="login" value="zur�ck ins Gefecht" onClick="return ergebnis()" tabindex="5" style="width:100"></td>
<td class="rr">&nbsp;</td>
</tr>
<tr>
<td class="rul">&nbsp;</td>
<td class="ru" colspan="4">&nbsp;</td>
<td class="rur">&nbsp;</td>
</tr>
</table>
</form></body></html>';
  }
  else //bereich im efta-style ausgeben
  {
  echo '<meta http-equiv="expires" content="0">
</head><body><script src="jssammlung.js" type="text/javascript"></script>
<form action="botcheck.php" method="post" name="loginform">';
  echo '<div id="ct_city">';
  echo '<table class="ct_title" width="100%" border="0" cellpadding="0" cellspacing="0">';
  echo '<tr><td align="center"><b class="ueber">&nbsp;Bot-Schutz-Abfrage&nbsp;</b></td></tr>';
  echo '</table><br>

<table width="600" border="0" cellpadding="0" cellspacing="0">
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colspan="4"><a href="index.php"><img src="imagegenerator.php" alt="Bild" border="0"></a></td>
<td class="rr">&nbsp;</td>
</tr>
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colspan="4">Zahl: &nbsp;&nbsp;<input type="Text" name="nummer" value="" tabindex="3" onbeforepaste="noPaste()" onpaste="noPaste()"></td>
<td class="rr">&nbsp;</td>
</tr>
<tr align="center">
<td height="25" class="rl">&nbsp;</td>
<td colSpan="4"><input type="Submit" name="login" value="zur&uuml;ck ins Spiel" onClick="return ergebnis()" tabindex="5" style="width:100"></td>
<td class="rr">&nbsp;</td>
</tr>
</table>
</form>/<div></body></html>';
  }
  exit();
}
elseif(($ums_session_start+$sv_session_lifetime)<time())
{
  $ums_one_way_bot_protection=1;
  session_register("ums_one_way_bot_protection");
}
*/
?>
