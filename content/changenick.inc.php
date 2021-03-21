<?php
include 'content/de/lang/'.$ums_language.'_changenick.lang.php';

$errmsg='';

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
//account anlegen
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
if ($_POST[button] AND $vb_ranglistenplatz<=$serverdata[$target][11][0])
{
  if($spielername!='')
  {
    //spielernamen auf gültige zeichen überprüfen
    if(!ereg ("^[[:alpha:]0-9öäü_=-]*$", $spielername)) $errmsg.='<font color="FF0000">'.$changenick_lang[fehlermsg1].': _-=).</font>';
    
    //überprüfen ob der spielername noch frei ist
    $sql = "SELECT * FROM ls_user WHERE spielername='$spielername';";
    $result = mysql_query($sql) OR die(mysql_error());
    $num = mysql_num_rows($result);

	if($num>0)$errmsg.='<font color="#FF0000">'.$changenick_lang[fehlermsg2].'</font>';

	if($errmsg=='')
	{
	  $ums_spielername=$spielername;
	  
	  //spielername in der db hinterlegen
	  mysql_query("UPDATE ls_user SET spielername='$spielername' WHERE user_id='$_SESSION[ums_user_id]'");
		
	  header("Location: index.php");
	}
	
  }
}


if($errmsg!='')echo '<br>'.$errmsg;

//daten abfragen
if($createok!=1)
{

echo '<form action="index.php" method="POST">';
echo '<br><br>'.$changenick_lang[spielername].': <input type="text" maxlength="20" name="spielername">
<input type="Submit" name="button" value="'.$changenick_lang[weiter].'">';
echo '</form><br><br>';
}

?>