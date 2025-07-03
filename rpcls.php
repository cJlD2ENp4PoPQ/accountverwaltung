<?php
session_start();
include "inc/header.inc.php";
include "inc/serverdata.inc.php";
include "functions.php";

//hier werden die daten vom server geholt und an die server.inc.php zurückgeliefert

//daten zurückliefern, die mit einem semikolon trennen;
echo $_REQUEST["target"];
echo ';';

//accountdaten vom server
echo doPost($serverdata[$_REQUEST["target"]][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&getaccountdata=1&id='.$_SESSION['ums_user_id'], $serverdata[$_REQUEST["target"]][5]);
?>
