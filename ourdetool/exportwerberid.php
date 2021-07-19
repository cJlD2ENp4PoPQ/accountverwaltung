<?php
include "../inccon.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Werber-ID Export</title>
</head>
<body>
<?

//alle spieler auslesen, die geworben worden sind
$result = mysql_query("SELECT * FROM ls_user WHERE werberid>0",$db);
  
while($row = mysql_fetch_array($result))
{
  $uid=$row[user_id];
  $werberid=$row[werberid];
  
  echo "UPDATE de_user_data SET werberid='$werberid' WHERE user_id=(SELECT user_id FROM de_login WHERE owner_id='$uid');<br>";
}


?>
</body>
</html>