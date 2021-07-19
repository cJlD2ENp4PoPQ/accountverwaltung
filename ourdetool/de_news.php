<?php
include "../inccon.php";
?>
<html>
<head>
<title>Admin - DE - News</title>
<?php include "cssinclude.php";?>
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#FF0000" alink="#FF0000" vlink="#FF0000">
<center>
<br><br><br><br><br>
<?
include "det_userdata.inc.php";

$news_lang["dateformat"]="d.m.Y";

//neuen beitrag speichern
if($absenden)
{
  $time=time();
  $language=intval($_REQUEST["sprache"]);
  
  mysql_query("INSERT INTO ls_news (language, betreff, nachricht, time) VALUES ('$language','$betreff','$nachricht','$time')");
  echo '<br><br><h1>Nachricht erfolgreich eingetragen</h1><br><br>';
}

if($edit)
{
  $time=time();
  $language=intval($_REQUEST["sprache"]);
  
  mysql_query("Update ls_news set betreff='$betreff', nachricht='$nachricht', time='$time', language='$language' where id='$id'");
  echo '<br><br><h1>Nachricht erfolgreich editiert</h1><br><br>';
}

if($action=="del")
{
mysql_query("DELETE FROM ls_news WHERE id='$id'");
echo '<br><br><h1>Nachricht erfolgreich gelöscht</h1><br><br>';
}

if($action=="aendern")
{

  $sel_news_edit = mysql_query("SELECT * FROM ls_news where id='$id'");

  $row=mysql_fetch_array($sel_news_edit);

echo '<form action="de_news.php?id='.$id.'" method="post" target="Hauptframe">
  <table border="1" width="750">
  <tr>
    <td colspan="2" align="center"><b>News ändern</b></td>
  </tr>
  <tr>
    <td>Betreff:</td>
    <td><input type="Text" name="betreff" maxlength="50" size="80" value="'.$row[betreff].'">
    Sprache: <select name="sprache">
<option value=1>deutsch</option>
<option value=2>englisch</option>
</select>
    </td>
  </tr>
  <tr>
    <td valign="top">Nachricht:</td>
    <td><textarea name="nachricht" cols="100" rows="15">'.$row[nachricht].'</textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="Submit" name="edit" value="Nachricht speichern">&nbsp;&nbsp;&nbsp;<input type="reset" value="Felder leeren"></td>
  </tr>
</table>
<input type="hidden" name="time" value="'.$row[time].'">
</form>';

}

if($action!="aendern")
{
?>


<form action="de_news.php" method="post" target="Hauptframe">
<table border="1" width="750">
  <tr>
    <td colspan="2" align="center"><b>News eintragen</b></td>
  </tr>
  <tr>
    <td>Betreff:</td>
    <td><input type="Text" name="betreff" maxlength="50" size="80">
    Sprache: <select name="sprache">
<option value=1>deutsch</option>
<option value=2>englisch</option>
</select>
    
    </td>
  </tr>
  <tr>
    <td valign="top">Nachricht:</td>
    <td><textarea name="nachricht" cols="100" rows="15"></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="Submit" name="absenden" value="Nachricht eintragen">&nbsp;&nbsp;&nbsp;<input type="reset" value="Felder leeren"></td>
  </tr>
</form>
</table>
<br><br><br><br>
<table border="0" width="750">
  <tr><td align="center"><h1>N a c h r i c h t e n</h1></td></tr>
  <?

  $sel_news=mysql_query("SELECT * FROM ls_news order by id desc");

  while($row=mysql_fetch_array($sel_news))
  {
  	$time=date($news_lang["dateformat"], $row["time"]);


  $nachricht = nl2br($row[nachricht]);
  if($row["language"]==1)$sprache='deutsch'; else $sprache='englisch';
  echo '<tr><td>
  <fieldset><table border="0" width="100%">
    <tr><td><b>Betreff:</b> '.$row[betreff].'</td>
    <td align="center" width="170"><b>Zeit:</b> '.$time.'</td>
    <td align="center">'.$sprache.'</td>
    <td align="center" width="100"><b><a href="de_news.php?id='.$row[id].'&action=del" onclick="return confirm(\'M&ouml;chtest du die Nachricht wirklich l&ouml;schen?\')">löschen</a>&nbsp;&nbsp;&nbsp;<a href="de_news.php?id='.$row[id].'&action=aendern">ändern</a></b></td>
  </tr>
  <tr>
    <td colspan="4"><hr>'.$nachricht.'</td>
  </tr></table></fieldset><br><br>';
  }


  ?>

</table>
<?
}
?>
</center>
</body>
</html>