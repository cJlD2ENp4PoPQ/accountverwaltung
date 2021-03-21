<?php
include 'content/de/lang/'.$ums_language.'_news.lang.php';

echo '<div align="left">';
   
  $output='';
  
  //unterscheiden ob die übersicht angezeigt werden soll, oder ob eine nachricht dargestellt wird
  
  if(isset($_REQUEST["newsid"]))//news anzeigen
  {
  	$newsid=intval($_REQUEST["newsid"]);
    $sel_news=mysql_query("SELECT * FROM ls_news WHERE id='$newsid'");
    while($rew=mysql_fetch_array($sel_news))
    {
      //datum formatieren
      $time=date($news_lang["dateformat"], $rew["time"]);
	  //überprüfen
      $output .= '<h3>'.$rew[betreff].'</a></h3><br><span class="datecontainer1">'.$time.'</span> '.nl2br($rew[nachricht]).'<br><br><a href="index.php?command=news">'.$news_lang[zurueck].'</a>';
    }
  }
  else //übersicht anzeigen
  {
  	//info für neue spieler
  	
  	//echo '<div class="rahmen0" align="center" style="color: #12d30d; background-color: #000000;">'.$news_lang["startinfo1"].'</div>';
  	
  	//feststellen in welcher sprache die news angezeigt werden sollen
	$sql = "SELECT newslang FROM ls_user WHERE user_id='$ums_user_id';";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$shownewslang=$row["newslang"];
  	
	if($shownewslang==0) //default, nur die news in der eigenen sprache anzeigen
	{
	  $sqllang='WHERE language='.intval($ums_language);
	}
	else //alle sprachen anzeigen
	{
	  $sqllang='';
	}
	
	if($_REQUEST["showallnews"]==1)
	{
	  $limit=99999;
	}
	else
	{
	  $limit=7;
	}
	
	$sql="SELECT *, SUBSTRING_INDEX(`nachricht`, '.', 3) AS `einleitung` FROM ls_news $sqllang ORDER BY time DESC Limit 0,$limit";
    $sel_news=mysql_query($sql, $db);
    $anz = mysql_num_rows($sel_news);
    while($rew=mysql_fetch_array($sel_news)){
		//datum formatieren
		$time=date($news_lang["dateformat"], $rew["time"]);
		//überprüfen
		$output .= '<h3><a href="index.php?command=news&newsid='.$rew['id'].'"><span class="datecontainer1">['.$time.']</span> '.$rew['betreff'].'</a></h3> '.$rew[einleitung].'. <a href="index.php?command=news&newsid='.$rew['id'].'">'.$news_lang["weiter"].'...</a><br><br>';
    }
    if($limit<>99999)$output .= '<a href="index.php?command=news&showallnews=1">'.$news_lang["zeigeallenews"].'...</a>';
  }
  
  echo $output;

  echo '</div>';

?>