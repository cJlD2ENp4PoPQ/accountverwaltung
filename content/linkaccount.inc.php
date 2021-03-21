<?php
include 'content/de/lang/'.$ums_language.'_linkaccount.lang.php';

//accountverbinden
if ($_POST[button])
{
  $target=intval($_REQUEST["server"]);
  $loginname=$_REQUEST["loginname"];
  $passwort=$_REQUEST["passwort"];

  //schauen ob alle daten eingegeben worden sind
  if ($loginname!='' AND $passwort!='')
  {
    //dann versuchen den account zu verbinden
    $result=doPost($serverdata[$target][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&linkaccount=1&id='.$ums_user_id.'&loginname='.$loginname.'&passwort='.$passwort, $serverdata[$target][5]);
    if ($result==1)
    {
      //alles ok, also  zur serverseite springen
      header("Location: index.php");
    }
    else $errmsg.='<font color="FF0000">'.$linkaccount_lang['msg_1'].'</font>';
  }
  else $errmsg.='<font color="FF0000">'.$linkaccount_lang['msg_2'].'</font>';
}


if($errmsg!='')echo $errmsg;
//daten auslesen
echo '<form action="index.php?command=linkaccount&server='.$_REQUEST["server"].'" method="POST">';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td colspan="2" align="center"><b>'.$linkaccount_lang['bestehendenaccounteinbinden'].'</b></td>
      </tr>
      <tr>
        <td colspan="2" align="center">'.$linkaccount_lang['msg_3_1'].' '.$serverdata[$_REQUEST["server"]][0].'-'.$linkaccount_lang['msg_3_2'].'.</td>
      </tr>
      <tr>
        <td width="180">'.$linkaccount_lang['loginname'].':</td>
        <td width="200"><input type="password" name="loginname" value=""></td>
      </tr>
      <tr>
        <td>'.$linkaccount_lang['passwort'].':</td>
        <td><input type="password" name="passwort" value=""></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="Submit" name="button" value="'.$linkaccount_lang['datenbestaetigen'].'"></td>
      </tr>
      ';

echo '</table>';
echo '</form>';


?>