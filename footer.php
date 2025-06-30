<?php
include 'content/de/lang/'.$_SESSION['ums_language'].'_footer.lang.php';

echo '<br style="clear:both;">';

echo '
<div id="footer">
				&copy; <a href="'.$GLOBALS['env_url_portal'].'" target="_blank">'.$footer_lang['dieewigen'].'</a>

        - <a href="'.$GLOBALS['env_url_impressum'].'" target="_blank">'.$footer_lang['impressum'].'</a>
        - <a href="'.$GLOBALS['env_url_datenschutz'].'" target="_blank">Datenschutz</a>
</div>';

//content-div
echo '</div>';

//sessionverlängerung
if(isset($_SESSION['ums_logins']) && $_SESSION['ums_user_id'] > 0){
echo "
<script>

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

