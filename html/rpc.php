<?php
$disablegzip = 1;
include "inccon.php";

//jeden aufruf über einen key checken
if ($_POST["authcode"] != $GLOBALS['env_rpc_authcode']) {
    exit;
}

//feststellen ob es einen account mit der user_id gibt
if ($_POST["isaccount_user_id"] == 1) {
    $id = intval($_POST["id"]);
    if ($id == 0) {
        $id = '-1';
    }
    $db_daten = mysqli_query($GLOBALS['dbi'], "SELECT user_id FROM ls_user WHERE user_id='$id';");
    $num = mysqli_num_rows($db_daten);
    if ($num == 1) {
        echo '1';
    } else {
        echo '0';
    }
}
