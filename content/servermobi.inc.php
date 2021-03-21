<?php
include 'content/de/lang/'.$ums_language.'_servermobi.lang.php';

//serverliste ausgeben
echo $servermobi_lang['serveruebersicht'].'<br>';
echo '<table border="0" cellpadding="3" cellspacing="0">
      <tr align="center">
        <td width="200"><b>'.$servermobi_lang['server'].'</td>
        <td width="150"><b>'.$servermobi_lang['kostenlos'].'</td>
        <td width="200"><b>'.$servermobi_lang['aktion'].'</td>
      </tr>';

for ($i=0;$i<=$sindex;$i++)
{
  //feststellen auf welchen servern er einen account hat
  $hasaccount=doPost($serverdata[$i][6].'rpc.php', 'authcode='.$GLOBALS['env_rpc_authcode'].'&isaccount=1&id='.$ums_user_id, $serverdata[$i][5]);
  if($hasaccount>0)
  {
  	$aktion='<a href="serverlogin.php?server='.$i.'" target="_blank">'.$servermobi_lang['login'].'</a>';
  	if($serverdata[$i][7]==1)$aktion.='&nbsp;<a href="index.php?command=credittransfer&server='.$i.'">'.$servermobi_lang['credittransfer'].'</a>';
  	
  }
  else $aktion='<a href="index.php?command=createaccount&server='.$i.'">'.$servermobi_lang['accountanlegen'].'</a>';
  

  echo "<tr>
          <td>".$serverdata[$i][0]." - ".$serverdata[$i][1]."</td>";
  if($serverdata[$i][7]==0 OR $serverdata[$i][7]==1)$kostenlos=$servermobi_lang['ja'];else $kostenlos=$servermobi_lang['nein'];
  echo   '<td><div align="center">'.$kostenlos.'</div></td>
          <td><div align="center">'.$aktion.'</div></td>
        </tr>';
}
echo '</table>';

?>