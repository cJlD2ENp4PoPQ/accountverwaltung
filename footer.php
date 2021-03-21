<?php
include 'content/de/lang/'.$_SESSION['ums_language'].'_footer.lang.php';

echo '<br style="clear:both;">';

echo '
<div id="footer">
				&copy; <a href="http://www.die-ewigen.com" target="_new">'.$footer_lang['dieewigen'].'</a>

        - <a href="http://bgam.es/impressum.php" target="_blank">'.$footer_lang['impressum'].'</a>
        - <a href="http://bgam.es/datenschutz.php" target="_blank">Datenschutz</a>
</div>';

//content-div
echo '</div>';




//wenn er sich das erste mal einloggt die e-mail-adresse im newsletter hinterlegen
//echo $_SESSION['ums_logins'];
if(isset($_SESSION['ums_logins']) && $_SESSION['ums_logins']<2){
	$result=mysql_query("SELECT reg_mail, newsletter_accept FROM ls_user WHERE user_id='$_SESSION[ums_user_id]'");
	$num = mysql_num_rows($result);
	if ($num>0){//datensatz wurde gefunden
		$row = mysql_fetch_array($result);
		if($row['newsletter_accept']==1){
      $mail=$row["reg_mail"];
      
      mysql_query("INSERT INTO de_newsletter SET reg_mail='$mail', sendmail=1, register=NOW();");

      /*
			$code=md5($mail.'Jf4nMd73');
			//den account im newsletter eintragen
			echo '<iframe src="http://die-ewigen.com/newsletter/inm.php?mail='.$mail.'&code='.$code.'&lang="1" framespacing="0"
				  frameborder="no" scrolling="no" width="0" height="0"></iframe>';
			*/
		}
	}
}

//sessionverlängerung
if(isset($_SESSION['ums_logins']) && $_SESSION['ums_user_id']>0 && $_SESSION['ums_mobi']==0){
echo "
<script type=\"text/javascript\" language=\"javascript\">

var http_sessreq = false;

var url = 'sa.php';
function sas()
{
  http_sessreq = false;

  if (window.XMLHttpRequest)  //FF
  {
    http_sessreq = new XMLHttpRequest();
    if (http_sessreq.overrideMimeType)
    {
      http_sessreq.overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject)// IE
  {
    try
    {
      http_sessreq = new ActiveXObject(\"Msxml2.XMLHTTP\");
    }
    catch (e)
    {
      try
      {
        http_sessreq = new ActiveXObject(\"Microsoft.XMLHTTP\");
      }
      catch (e)
      {}
    }
  }
  if (!http_sessreq)
  {
    alert('$footer_lang[msg_1]');
    return false;
  }
  http_sessreq.open('POST', url, true);
  http_sessreq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  http_sessreq.onreadystatechange = sa;
  http_sessreq.send('');
}";

echo "function sa(){}";

echo '
function md()
{
  sas();
  setTimeout("md()", 1200000);
}
md()
';

if($ums_user_id>0)
echo "</script>";
}
?>

