<?php
include "../inccon.php";
//include "../outputlib.php";

$time=time();
?>
<html>
<head>
<title>Tickets</title>
<?php include "cssinclude.php";?>
</head>
<body>
<div align="center">
<?php

include "det_userdata.inc.php";

if(isset($_REQUEST['showtid'])){
	$ticket_id=intval($_REQUEST['showtid']);
	$db_daten=mysql_query("SELECT * FROM ls_tickets WHERE id='$ticket_id'",$db);
	$num = mysql_num_rows($db_daten);
	if($num>0){
		$row = mysql_fetch_array($db_daten);
		$user_id=$row['user_id'];
		//�berpr�fen ob das ticket dem spieler geh�rt
			//�berpr�fen ob eine antwort eingef�gt werden soll
			if($_REQUEST['reply']==1)
			{
				$messagesql=trim($_REQUEST['nachricht']);
				//echo 'A: '.$messagesql;
				$messagesql=htmlspecialchars($messagesql, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
				//echo 'B: '.$messagesql;
				$messagesql=str_replace('\r\n', '<br>', $messagesql);

				$messagesql=utf8_decode($messagesql);
				//echo 'C: '.$messagesql;
				
				//nachricht hinterlegen
				mysql_query("INSERT INTO ls_tickets_posts SET ticket_id='$ticket_id', created='$time', poster='$det_email', message='$messagesql';", $db);

				//ticketstatus anpassen
				mysql_query("UPDATE ls_tickets SET modified='$time', status=1, supporter='$det_email' WHERE id='$ticket_id';", $db);
			}

			if($_REQUEST['close']==1)
			{
				//ticketstatus anpassen
				mysql_query("UPDATE ls_tickets SET modified='$time', status=1, supporter='$det_email' WHERE id='$ticket_id';", $db);
				$row['status']=1;
			}				

			//nachricht ausgeben
			if($row['status']==0)$status='Ticket ist offen';else $status='Ticket ist geschlossen';
			echo '<div style="width: 640px; padding: 5px; background-color: #222222;">'.$row['thema'].' ('.$status.')</div>';

			//die einzelnen posts
			$db_daten=mysql_query("SELECT * FROM ls_tickets_posts WHERE ticket_id='$ticket_id' ORDER BY created ASC",$db);
			$spielername='';
			while($row = mysql_fetch_array($db_daten))
			{
				if($spielername=='')$spielername=$row['poster'];
				//header
				if($row['poster']==$spielername)
				{
					$bgcolor='#444444';
					echo '<div style="text-align:left; width: 640px; margin-top: 2px; padding: 5px; background-color: '.$bgcolor.';"><a href="http://login.bgam.es/ourdetool/idinfo.php?UID='.$user_id.'">'.$row['poster'].'</a> - '.date("G:i:s d.m.Y", $row['created']).'</div>';
				}
				else 
				{
					$bgcolor='#446644';
					echo '<div style="text-align:left; width: 640px; margin-top: 2px; padding: 5px; background-color: '.$bgcolor.';">'.$row['poster'].' - '.date("G:i:s d.m.Y", $row['created']).'</div>';
				}

				//body
				if($row['poster']==$spielername)$bgcolor='#222222';else $bgcolor='#226622';
				echo '<div style="text-align:left; width: 640px; margin-top: 1px; padding: 5px; background-color: '.$bgcolor.';">'.utf8_encode($row['message']).'</div>';
			}

			//antwortformular
			echo '<form action="tickets.php?reply=1&showtid='.$ticket_id.'" method="POST">';
			echo '<br>Nachricht:<br>';
			echo '<textarea rows="12" name="nachricht" cols="75"></textarea>'; 

			echo '<div align="center"><br><input type="submit" name="bieten" value="Nachricht senden"></div>';

			echo '</form>';

			echo '<br>Das Ticket ben&ouml;tigt keine Antwort: <a href="tickets.php?close=1&showtid='.$ticket_id.'">Ticket schlie&szlig;en</a>';
	}
}
else 
{
	echo '<div style="font-size: 20px;">Offene Tickets</div>';

		$db_daten=mysql_query("SELECT * FROM ls_tickets WHERE status=0  ORDER BY created DESC",$db);
    	$num = mysql_num_rows($db_daten);
    	if($num>0)
    	{
    		//kopf
    		echo '<table width="100%">';
    		echo '<tr><td>Betreff</td><td>User</td><td>erstellt</td><td>letzte &auml;nderung</td><td>Supporter</td></tr>';
    	
    		while($row = mysql_fetch_array($db_daten))
    		{
    			//spielernamen auslesen
    			$db_datenx=mysql_query("SELECT * FROM ls_user WHERE user_id=".$row['user_id'],$db);
    			$rowx = mysql_fetch_array($db_datenx);
    			
    			echo '<tr>';
    			echo '<td><a href="tickets.php?showtid='.$row['id'].'">'.$row['thema'].'</a></td>';
    			echo '<td><a href="idinfo.php?UID='.$row['user_id'].'">'.$rowx['spielername'].'</a></td>';
    			echo '<td>'.date("H:i:s d.m.Y", $row['created']).'</td>';
    			echo '<td>'.date("H:i:s d.m.Y", $row['modified']).'</td>';
    			if($row['supporter']=='')$status='noch keiner';else $status=$row['supporter'];
    			echo '<td>'.$status.'</td>';
    		
    			echo '</tr>';
    		}
    	
    		echo '</table>';
    	}
    	else echo 'Es gibt keine offenen Tickets.';


echo '<br><hr><br>';

echo '<div style="font-size: 20px;">Beantwortete Tickets</div>';

		$db_daten=mysql_query("SELECT * FROM ls_tickets WHERE status=1  ORDER BY created DESC LIMIT 50",$db);
    	$num = mysql_num_rows($db_daten);
    	if($num>0)
    	{
    		//kopf
    		echo '<table width="100%">';
    		echo '<tr><td>Betreff</td><td>Spieler</td><td>erstellt</td><td>letzte &auml;nderung</td><td>Supporter</td></tr>';
    	
    		while($row = mysql_fetch_array($db_daten))
    		{
    			//spielernamen auslesen
    			$db_datenx=mysql_query("SELECT * FROM ls_user WHERE user_id=".$row['user_id'],$db);
    			$rowx = mysql_fetch_array($db_datenx);
    			
    			echo '<tr>';
    			echo '<td><a href="tickets.php?showtid='.$row['id'].'">'.$row['thema'].'</a></td>';
    			echo '<td><a href="idinfo.php?UID='.$row['user_id'].'">'.$rowx['spielername'].'</a></td>';
    			echo '<td>'.date("H:i:s d.m.Y", $row['created']).'</td>';
    			echo '<td>'.date("H:i:s d.m.Y", $row['modified']).'</td>';
    			if($row['supporter']=='')$status='noch keiner';else $status=$row['supporter'];
    			echo '<td>'.$status.'</td>';
    		
    			echo '</tr>';
    		}
    	
    		echo '</table>';
    	}
    	else echo 'Es gibt keine beantworteten Tickets.';

}
?>
</div>
</body>
</html>