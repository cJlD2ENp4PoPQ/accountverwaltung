<?php
include 'content/de/lang/'.$ums_language.'_server.lang.php';

if($_SESSION['ums_user_id']!=1){
	//die('Wartungsarbeiten. Die Ticks stehen.');
}

//ggf. creditinfo l�schen
if($_REQUEST["do"]==1)
{
  mysql_query("UPDATE ls_credits SET seen=1 WHERE user_id='$_SESSION[ums_user_id]'");
}

//alle Server darstellen

//feststellen auf welchen servern er einen account hat
//javascriptteil der serverliste generieren, z.b. beschreibungen und tickzeiten
//zus�tzlich noch die ben�tigen javascript-arrays definieren
echo '<script language="javascript">';
echo 'var stip = new Array();';
echo 'var atip = new Array();';
echo 'var btip = new Array();';
echo 'var servertyp = new Array();';
echo 'btip[0] = ["'.$server_lang['login'].'","'.$server_lang['msg_1'].'"];';
echo 'btip[1] = ["'.$server_lang['loginexternerbrowser'].'","'.$server_lang['msg_2'].'"];';
echo 'btip[2] = ["'.$server_lang['anmeldung'].'","'.$server_lang['msg_3'].'"];';
echo 'btip[3] = ["'.$server_lang['urlaubsmodusbeenden'].'","'.$server_lang['msg_4'].'"];';
echo 'btip[4] = ["'.$server_lang['credittransfer'].'","'.$server_lang['msg_5'].'"];';
echo 'btip[5] = ["'.$server_lang['spielzeiterwerben'].'","'.$server_lang['msg_6'].'"];';

for ($i=0;$i<=$sindex;$i++)
{
  if($serverdata[$i][2]!='')
  $sstr=$server_lang['wtick'].': '.$serverdata[$i][2].'<br>'.
        $server_lang['ktick'].': '.$serverdata[$i][3];
  else $sstr='';
  $stip[$i] = '<b>'.$serverdata[$i][0].' - '.$serverdata[$i][1].'</b><br>'.$sstr.'"];';
}
//servertypen (billing) in nen array packen
for ($i=0;$i<=$sindex;$i++)
{
  echo 'servertyp['.$i.'] = '.$serverdata[$i][7].';';
}

echo "</script>";

//auslesen ob der externe browserlink angezeigt werden soll und ob man noch alte tickets hat
$result = mysql_query("SELECT showeblink, tickets FROM ls_user WHERE user_id='$_SESSION[ums_user_id]'");
$row = mysql_fetch_array($result);
$showeblink=$row["showeblink"];
$tickets=0;//$row["tickets"];


//�berpr�fen ob man evtl. credits als werbebonus bekommen hat
$werbeboni='';
$result = mysql_query("SELECT * FROM ls_credits WHERE user_id='$_SESSION[ums_user_id]' AND seen=0");
while($row = mysql_fetch_array($result))
{
  if($row["credits"]>0)
  {
	if($werbeboni!='')$werbeboni.=', ';
    $werbeboni.=$row["credits"];
  }
}
if($werbeboni!='')
{
  echo $server_lang['freundschaftsboni'].': '.$werbeboni.' <a href="index.php?command=server&do=1">'.$server_lang['freundschaftsboniinfoloeschen'].'</a>';
}

$containerold=-1;

//serverliste ausgeben
//echo '<table width="97%" border="0" cellpadding="0" cellspacing="0">';
echo '<div style="width: 100%; position: relative; color: #000000;">';
$stipids='';
for ($i=0;$i<=$sindex;$i++)
{
  //�berpr�fen ob der server in der cooperation angezeigt wird
  if(in_array($ums_cooperation, $serverdata[$i][10])){
	//schauen ob sich der spieltyp �ndert
    if($containerold!=$serverdata[$i]['container']){
      //echo '<tr align="center"><td colspan="4"><br><b>'.$server_lang[$gamename[$serverdata[$i][8]]].'</b></td></tr>';
      //spielnamen ausgeben
      echo '<div style="margin-bottom: 15px; margin-top: 20px; width: 100%; float: left; font-size: 24px; color: '.$serverdata[$i]['containercolor'].'"><b>'.$gamename[$serverdata[$i]['container']].'</b></div>';
      //echo '<span style="width: 100%; float: right;"><img style="float: left;" src="img/textgt'.$serverdata[$i][8].'.gif">';
      
      //serverempfehlungungen
	  /*
      //de
      if($serverdata[$i][8]==1 AND $server_lang['empfehlungde']!='')
      {
        //echo '<tr><td colspan="4"><div align="center" style="color: #00FF00;">'.$server_lang["empfehlungde"].'</div></td></tr>';
        echo '<font color="#ffec6b">'.$server_lang["empfehlungde"].'</font>';
      }       
      //se
      if($serverdata[$i][8]==2 AND $server_lang['empfehlungse']!='')
      {
        //echo '<tr><td colspan="4"><div align="center" style="color: #00FF00;">'.$server_lang["empfehlungse"].'</div></td></tr>';	
        echo '<font color="#ffec6b">'.$server_lang["empfehlungse"].'</font>';
      } 
      //alu
      
      //div des spielnamens schlie�en
      echo '</span>';
	   */
	  //Hinweistext bzgl. Testserver
	  if($gamename[$serverdata[$i]['container']]=='DIE EWIGEN'){
		  //$hinweis='<div style="padding: 5px; border: 1px solid #39F; margin-bottom: 5px; color: #FFFFFF; float: left; width: 98.5%;">SDE ist der offizielle Testserver. Dort werden neue Sachen ausprobiert und wer an DE mitwirken und informiert sein m&ouml;chte, sollte dort einen Account haben.</div>';
      $hinweis='
      <div style="padding: 5px; border: 1px solid #39F; margin-bottom: 5px; color: #FF0000; float: left; width: 98.5%;">
        ACHTUNG: DDE ist TESTSERVER. <span id="hidden_show1" style="cursor: pointer; color: #3399FF;" onclick="showHiddenInfo(1);">mehr...</span>
          <span id="hidden_info1" style="display: none;">
          DDE hat SDE tempor&auml;r als Testserver abgel&ouml;st. Dort wird eine neue DE-Version entwickelt. Wer auf die Entwicklung Einfluss haben m&ouml;chte, dem wird empfohlen sich dort zu beteiligen. Es ist nat&uuml;rlich keine Pflicht dort mitzuwirken, aber wer darauf verzichtet, sollte damit rechnen, dass seine Meinung nicht beachtet wird und sp&auml;tere Beschwerden u.U. auch nichts mehr bringen.
          </span>
       </div>';
	  }

	}else{
		$hinweis='';
	}
    
	echo $hinweis;
	
    //echo '<div style="width: 160px; height: 120px; border: 1px solid '.$serverdata[$i]['containercolor'].'; border-radius: 5px; background-image: url(img/serverbg'.$serverdata[$i]['containerbg'].'.jpg); float: left; margin-right: 5px; margin-bottom: 5px; position: relative;">';
	echo '<div class="game_box"><div class="game_box_'.($serverdata[$i]['containerbg']).'" style="background: top center url(img/game_box_'.($serverdata[$i]['containerbg']+1).'.png);">';

  /*
  //flagge bestimmen
  if($serverdata[$i][9]==1){
    $flagge='de.png';
  }else{
    $flagge='en.png';
  }
  //echo '<span style="top: -4px; left: 0px; position: absolute;"><img style="border-top-left-radius: 4px;" src="img/'.$flagge.'"></span>';
  echo '<img src="img/'.$flagge.'" alt="DE" class="game_lang_'.($serverdata[$i]['containerbg']).'" />';
  */

    //ausgabe des servernamens    
    echo '<span class="game_short" id="stip'.$i.'" title="'.$stip[$i].'" rel="tooltip"><b>'.$serverdata[$i][0].'</b></span>';
    
    //ausgabe der serverinformation
    //echo '<span style="top: 4px; left: 28px; position: absolute;"><img id="stip'.$i.'" style="vertical-align: middle;" src="img/i1.gif" border="0" title="'.$stip[$i].'"></span>';
    if($stipids!='')$stipids.=',';
    $stipids.="#stip$i";
    
    //credittransfer-link
    echo '<div class="game_credit_'.($serverdata[$i]['containerbg']).'" id="id'.$i.'_3" title="'.$server_lang['credittransfer'].'�'.$server_lang['msg_5'].'"></div>';
    
    //empfehlung f�r neue spieler
    if($serverdata[$i][12]==1) echo '<span style="left: 6px; top: 28px; position: absolute; width: 148px; font-size: 12px; text-align: center;">'.$server_lang['empfehlung'].'</span>';
    
    //spielerstatus/informationen
    echo '<div style="top: 45px; left: 27px; position: absolute; width: 106px; text-align: center;" id="id'.$i.'_1">'.$server_lang['ueberpruefe'].'</div>';

    //kostenloser server
    if($serverdata[$i][7]==2)echo '<span style="left: 6px; top: 70px; position: absolute; width: 148px; text-align: center;">'.$server_lang['kostenpflichtig'].'</span>';
    
    //login-link
    echo '<div style="bottom: 2px; left: 5px; width: 148px; position: absolute; font-size: 22px; text-align: center;" id="id'.$i.'_2">-</div>';


    echo '</div></div>';
    
    $containerold=$serverdata[$i]['container'];
  }
}
echo '</div>';
//echo '</table>';



echo "
<script type=\"text/javascript\" language=\"javascript\">
$(document).ready(function() {

//per javascript den accountstatus auslesen	  
var http_request = new Array();
var showeblink = ".$showeblink.";

http_request[0] = false;
http_request[1] = false;
http_request[2] = false;
http_request[3] = false;
http_request[4] = false;
http_request[5] = false;
http_request[6] = false;
http_request[7] = false;
http_request[8] = false;
http_request[9] = false;
http_request[10] = false;
http_request[11] = false;
http_request[12] = false;
http_request[13] = false;
http_request[14] = false;
http_request[15] = false;

var url = 'rpcls.php';
function accountcheck(target)
{
  http_request[target] = false;

  if (window.XMLHttpRequest)  //FF
  {
    http_request[target] = new XMLHttpRequest();
    if (http_request[target].overrideMimeType)
    {
      http_request[target].overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject)// IE
  {
    try
    {
      http_request[target] = new ActiveXObject(\"Msxml2.XMLHTTP\");
    }
    catch (e)
    {
      try
      {
        http_request[target] = new ActiveXObject(\"Microsoft.XMLHTTP\");
      }
      catch (e)
      {}
    }
  }
  if (!http_request[target])
  {
    alert('$server_lang[msg_7]');
    return false;
  }
  http_request[target].open('POST', url, true);
  http_request[target].setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  if(target==0)http_request[target].onreadystatechange = checkdata0;
  if(target==1)http_request[target].onreadystatechange = checkdata1;
  if(target==2)http_request[target].onreadystatechange = checkdata2;
  if(target==3)http_request[target].onreadystatechange = checkdata3;
  if(target==4)http_request[target].onreadystatechange = checkdata4;
  if(target==5)http_request[target].onreadystatechange = checkdata5;
  if(target==6)http_request[target].onreadystatechange = checkdata6;
  if(target==7)http_request[target].onreadystatechange = checkdata7;
  if(target==8)http_request[target].onreadystatechange = checkdata8;
  if(target==9)http_request[target].onreadystatechange = checkdata9;
  if(target==10)http_request[target].onreadystatechange = checkdata10;
  if(target==11)http_request[target].onreadystatechange = checkdata11;
  if(target==12)http_request[target].onreadystatechange = checkdata12;
  if(target==13)http_request[target].onreadystatechange = checkdata13;
  if(target==14)http_request[target].onreadystatechange = checkdata14;
  if(target==15)http_request[target].onreadystatechange = checkdata15;

  http_request[target].send('accountcheck=1&target='+target);
}

function managedata(data){
  //alert(data);

  var a = data.split(';');
  if(a[1]>0){
    var istr='';
    if(a[2]==1){

      sstr = unescape(a[9]);
      atip[a[0]] = '<b>$server_lang[accountdaten]'+'</b><br>'+sstr;

      document.getElementById('id'+a[0]+'_1').innerHTML = '$server_lang[aktiv]' + ' <img id=\"statusimg_'+a[0]+'\" style=\"vertical-align: middle;\" src=\"img/i1.gif\" border=\"0\" rel=\"tooltip\" title=\"'+atip[a[0]]+'\">';
      if(showeblink==1) istr=istr+' <a href=\"serverlogin.php?server='+a[0]+'&eb=1\"><span id=\"loginimg_'+a[0]+'\"><b>".$server_lang['login']."</b></span></a>';
      else istr=istr+'<a href=\"serverlogin.php?server='+a[0]+'\" target=\"_blank\"><span id=\"loginimg_'+a[0]+'\"><b>".$server_lang['login']."</b></span></a>';
      //if(servertyp[a[0]]==2 && $tickets>0)istr=istr+' <a href=\"index.php?command=getplaytime&server='+a[0]+'\"><img src=\"img/i4.gif\" border=\"0\" onMouseOver=\"stm(btip[5],Style[0])\" onMouseOut=\"htm()\"></a>';
      document.getElementById('id'+a[0]+'_2').innerHTML = istr;
      if(servertyp[a[0]]==1)document.getElementById('id'+a[0]+'_3').innerHTML='<a href=\"index.php?command=credittransfer&server='+a[0]+'\"><img src=\"img/credits.gif\"></a>';

    }else if(a[2]==0){
      
      atip[a[0]] = '<b>$server_lang[accounteinrichtung]</b><br>$server_lang[msg_8]';
      document.getElementById('id'+a[0]+'_1').innerHTML = '$server_lang[wirdeingerichtet]' + ' <img id=\"statusimg_'+a[0]+'\" style=\"vertical-align: middle;\" src=\"img/i1.gif\" border=\"0\" rel=\"tooltip\" title=\"'+atip[a[0]]+'\">';
      document.getElementById('id'+a[0]+'_2').innerHTML = '-';

    }else if(a[2]==2){

      sstr = unescape(a[9]);
      atip[a[0]] = '<b>$server_lang[accountdaten]'+'</b><br>'+sstr;
          
      document.getElementById('id'+a[0]+'_1').innerHTML = '$server_lang[gesperrt]' + ' <img id=\"statusimg_'+a[0]+'\" style=\"vertical-align: middle;\" src=\"img/i1.gif\" border=\"0\" rel=\"tooltip\" title=\"'+atip[a[0]]+'\">';
      document.getElementById('id'+a[0]+'_2').innerHTML = '-';
    
    }else if(a[2]==3){

      sstr = unescape(a[9]);
      atip[a[0]] = '<b>$server_lang[accountdaten]'+'</b><br>'+sstr;
          
      document.getElementById('id'+a[0]+'_1').innerHTML = '$server_lang[urlaubsmodus]'+' <img id=\"statusimg_'+a[0]+'\" style=\"vertical-align: middle;\" src=\"img/i1.gif\" border=\"0\" rel=\"tooltip\" title=\"'+atip[a[0]]+'\">';
      if(showeblink==1) istr=istr+' <a href=\"serverlogin.php?server='+a[0]+'&eb=1\"><img id=\"loginimg_'+a[0]+'\" src=\"img/i2.gif\" border=\"0\" rel=\"tooltip\" title=\"<b>$server_lang[urlaubsmodusbeenden]</b><br>$server_lang[msg_4]\"></a>';
      else istr=istr+'<a href=\"serverlogin.php?server='+a[0]+'\" target=\"_blank\"><img id=\"loginimg_'+a[0]+'\" src=\"img/i2.gif\" border=\"0\" rel=\"tooltip\" title=\"<b>$server_lang[urlaubsmodusbeendet]</b><br>$server_lang[msg_4]\"></a>';
      document.getElementById('id'+a[0]+'_2').innerHTML = istr;
      
    }else if(a[2]==4){

      sstr = unescape(a[9]);
      atip[a[0]] = '<b>$server_lang[accountdaten]'+'</b><br>'+sstr;
          
      document.getElementById('id'+a[0]+'_1').innerHTML = '$server_lang[umzugsmodus]'+' <img id=\"statusimg_'+a[0]+'\" style=\"vertical-align: middle;\" src=\"img/i1.gif\" border=\"0\" rel=\"tooltip\" title=\"'+atip[a[0]]+'\">';
      document.getElementById('id'+a[0]+'_2').innerHTML = '-';
    }
  }else{
    document.getElementById('id'+a[0]+'_1').innerHTML = '-';
    document.getElementById('id'+a[0]+'_2').innerHTML = '<a href=\"index.php?command=createaccount&server='+a[0]+'\"><span id=\"loginimg_'+a[0]+'\"><b>".$server_lang['anmeldung']."</b></span></a>';
    
  }
  setTooltip();
}
";

for ($i=0;$i<=$sindex;$i++){
  if(in_array($ums_cooperation, $serverdata[$i][10]))
  echo "
  function checkdata".$i."()
  {
    if (http_request[".$i."].readyState == 4)
    {
      if (http_request[".$i."].status == 200)
      {
        if(http_request[".$i."].responseText!='-1')
        {
          managedata(http_request[".$i."].responseText);
        }
      }else{
        alert('$server_lang[msg_9]');
      }
    }
  }";
}

//die ajax-instanzen starten
for ($i=0;$i<=$sindex;$i++){
  if(in_array($ums_cooperation, $serverdata[$i][10]))echo "accountcheck('".$i."');";
}
?>
});
</script>