<?php
$logdb = @mysql_connect("87.106.217.144","loguser","n9Vjr8Hc", true) or die("Keine Verbindung zur Datenbank möglich.");
mysql_select_db("gameserverlogdata", $logdb);
?>