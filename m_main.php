<?php
include 'content/de/lang/'.$_SESSION['ums_language'].'_m_main.lang.php';


$um='';
if(isset($_SESSION['ums_user_id']) && $_SESSION["ums_user_id"]>0){
	echo '<div class="topnav" id="ls_topnav">';
	
	if(!isset($_REQUEST["command"]) || $_REQUEST["command"]==""){
		$_REQUEST["command"]='server_direct';
	}
	//server/spielen
	$cssclass='';
	//if($_REQUEST["command"]=="server"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	echo '<a '.$cssclass.'href="index.php?command=server_direct">'.$m_main_lang['server'].'</a>';

	//echo '<a '.$cssclass.'href="index.php?command=server">Spielen (alt)</a>';

	//news
	//if($_REQUEST["command"]=="news"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	//echo '<a '.$cssclass.'href="index.php?command=news">'.$m_main_lang['news'].'</a>';

	//credits
	//if($_REQUEST["command"]=="credits"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	//echo '<a '.$cssclass.'href="index.php?command=credits">'.$m_main_lang['credits'].'</a>';

	//tickets
	//if($_REQUEST["command"]=="tickets"){$fs[0]='<b>';$fs[1]='</b>';}else{$fs[0]='';$fs[1]='';}
	//echo $fs[0].'<span><a href="index.php?command=tickets">'.$m_main_lang['tickets'].'</a></span>'.$fs[1];

	//premium
	//if($_REQUEST["command"]=="premium"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	//echo '<a '.$cssclass.'href="index.php?command=premium">'.$m_main_lang['premium'].'</a>';
 
	//community
	  /*
	if($ums_cooperation==0)
	{  
	  if($_REQUEST["command"]=="community" OR $_REQUEST["command"]=="communitytl")
	  {
		$um='';
		$fs[0]='<b>';$fs[1]='</b>';
		if($_REQUEST["command"]=="communitytl"){$fs1[0]='<b>';$fs1[1]='</b>';}else{$fs1[0]='';$fs1[1]='';}
		if($_REQUEST["command"]=="community"){$fs2[0]='<b>';$fs2[1]='</b>';}else{$fs2[0]='';$fs2[1]='';}

		$um='<br><br>'.$fs1[0].'<a href="index.php?command=communitytl"><div class="b1left"></div>
		  <div class="b1"><div class="menutextspacer">'.$m_main_lang['rangliste'].'</div></div><div class="b1right"></div></a>'.$fs1[1];
		//$um.=$fs2[0].'<a href="index.php?command=community"><div class="b1left"></div>
		$um.=$fs2[0].'<a href="geomap.php" target="_blank"><div class="b1left"></div>
		  <div class="b1"><div class="menutextspacer">'.$m_main_lang['karte'].'</div></div><div class="b1right"></div></a>'.$fs2[1];      


	  }
	  else{$fs[0]='';$fs[1]='';}

	  echo $fs[0].'<a href="index.php?command=communitytl"><div class="b1left"></div>
	  <div class="b1"><div class="menutextspacer">'.$m_main_lang['community'].'</div></div><div class="b1right"></div></a>'.$fs[1];
	}*/

	//accountdaten
	//if($_REQUEST["command"]=="account"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	echo '<a '.$cssclass.'href="index.php?command=account">'.$m_main_lang['accountdaten'].'</a>';
	
	
	//forum
	//if($_REQUEST["command"]=="forum"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	if(isset($GLOBALS['env_enable_forum_connect']) && $GLOBALS['env_enable_forum_connect']==1){
		echo '<a '.$cssclass.'href="index.php?command=forum">'.$m_main_lang['forum'].'</a>';
	}
  
	//Support
	if(isset($GLOBALS['env_enable_support_page']) && $GLOBALS['env_enable_support_page']==1){
		if($_REQUEST["command"]=="support"){
			$um='<div style="width: 100%">';

			if($_REQUEST["page"]=="1"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
			$um.='<a '.$cssclass.'href="index.php?command=support&page=1">'.$m_main_lang['ticketold'].'</a>';

			if($_REQUEST["page"]=="2"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}			
			$um.='<a '.$cssclass.'href="index.php?command=support&page=2">'.$m_main_lang['ticketnew'].'</a>';

			$um.='</div>';
			//fetter supportbutton
			$cssclass=' class="button1 textbold"';
		}else{
			$cssclass=' class="button1"';
		}

		echo '<a href="index.php?command=support&page=1">'.$m_main_lang['support'].'</a>';
	}
	
	if(isset($GLOBALS['env_enable_de_kb_db']) && $GLOBALS['env_enable_de_kb_db']==1){
		echo '<a href="index.php?command=de_kb">DE-KB</a>';
	}

  
	//logout
	echo '<a href="index.php?command=logout">'.$m_main_lang['logout'].'</a>';
  
	//untermenü ausgeben
	//echo $um;

	echo '
	<a href="javascript:void(0);" class="icon" onclick="burgerMenu()">&#9776;</a>
  
  <script>
function burgerMenu() {
    var x = document.getElementById("ls_topnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>


	';

	//div menucontainer end
	echo '</div>';

}else{
	echo '<div class="topnav_nli">';

	if(!isset($cssclass)){
		$cssclass='';
	}

	//login
	//if($_REQUEST["command"]=="login" OR $_REQUEST["command"]==""){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	echo '<a '.$cssclass.'href="index.php?command=login">'.$m_main_lang['login'].'</a>';
	
	//account anlegen
	//if($_REQUEST["command"]=="register"){$cssclass=' class="button1 textbold"';}else{$cssclass=' class="button1"';}
	echo '<a '.$cssclass.'href="index.php?command=register">'.$m_main_lang['accountanlegen'].'</a>';

	//div menucontainer end
	echo '</div>';	
}


?>