<?php
include 'content/de/lang/'.$ums_language.'_community.lang.php';



    # Koordinaten für die deutsche Landesgrenze 
    /*
    define ('COORD_LEFT', 6.0); 
    define ('COORD_RIGHT', 15.0);
     
    define ('COORD_TOP', 55.0); 
    define ('COORD_BOTTOM', 47.3); */

    /*
	define ('COORD_LEFT', 5.5);
	define ('COORD_RIGHT', 15.5);
	define ('COORD_TOP', 55.1);
	define ('COORD_BOTTOM', 47.2);*/

	define ('COORD_LEFT', 5.9);
	define ('COORD_RIGHT', 15.1);
	define ('COORD_TOP', 54.9);
	define ('COORD_BOTTOM', 47.2);    
    
    class geoMap{ 
         
        var $m_image; 
        var $m_coord_lat; 
        var $m_coord_lon; 
        var $m_imageSize; 
        var $m_range; 
         
        # Überprüfen ob Datei existiert und Karte anzeigen 
        function geoMap($image, $title = '', $border = 0){ 
            if (!@file_exists($image)){ 
                die("error 1"); 
            } 
            $this->m_image = $image; 
            //print "<img src='$image' title='$title' border='$border'>"; 
        } 
         
        # Wird momentan nicht gebraucht 
        function m_GetImageSize(){ 
            $this->m_imageSize = GetImageSize ($this->m_image); 
        } 
         
        # Range der Map setzen 
        function m_setRange($left, $right, $top, $bottom){ 
            $this->range['LEFT']     = $left; 
            $this->range['RIGHT']     = $right; 
            $this->range['TOP']     = $top; 
            $this->range['BOTTOM']     = $bottom; 
        } 
         
        # Koordinaten angeben, für die der Standort berechnet werden soll 
        function m_setCoords($lat, $lon){ 
            $this->m_coord_lat = $lat; 
            $this->m_coord_lon = $lon; 
        } 
         
        # Punkt auf Karte berechnen 
        function m_calPoints(){ 
            $this->m_GetImageSize(); 
            if ($this->range['RIGHT'] > $this->m_imageSize[0] or $this->range['LEFT'] < 0){ 
                die('error 2'); 
            } 
            if ($this->range['BOTTOM'] > $this->m_imageSize[1] or $this->range['TOP'] < 0){ 
                die('error 3'); 
            } 
            $point['LEFT'] = ($this->range['RIGHT'] - $this->range['LEFT']) / (COORD_RIGHT - COORD_LEFT) * ($this->m_coord_lon - COORD_LEFT) + $this->range['LEFT']; 
            $point['TOP'] = ($this->range['BOTTOM'] - $this->range['TOP']) / (COORD_TOP - COORD_BOTTOM) * (COORD_TOP - $this->m_coord_lat) + ($this->range['TOP'] - 4); 
            return $point; 
        } 
         
        # Stadt auf Karte anzeigen lassen 
        function m_printPoints($ort, $like = 0){ 
            if ($like == 1){ 
                $ort = "%$ort%"; 
            } 
            //$sql = "SELECT * FROM geo WHERE geo_ort LIKE '$ort' LIMIT 1"; 
            //$sql = "SELECT * FROM ls_geodata ORDER BY RAND() LIMIT 100"; 
			
           $time=time();
           
$sql="SELECT ls_geodata.plz, ls_geodata.lon, ls_geodata.lat, ls_geodata.ort FROM ls_geodata LEFT JOIN ls_user on(ls_geodata.plz = ls_user.plz)
WHERE UNIX_TIMESTAMP(last_login)+2592000>$time GROUP BY ls_user.plz";
            
//echo $sql;            
            $result = mysql_query($sql); 
             
            while ($row = mysql_fetch_assoc($result)){ 
                $this->m_setCoords($row['lat'], $row['lon']); 
                $point = $this->m_calPoints(); 
                print "<div style=\"position:absolute; left:".round($point['LEFT'])."px; top:".round($point['TOP']). 
                        "px;\"><img src=\"img/x1.gif\" title=\"".$row['ort']."  |  ".$row['plz']."\" alt=\"".$row['ort']."  |  ".$row['plz']."\" border=\"0\"></div>"; 
            } 
        } 
    } # End Class geoMap
	//require('geoMap.inc.php');

	# Verbindung zur Datenbank
	//$link = @mysql_connect('hostname', 'usernam', 'passwort')or die('Falsche Verbindungsdaten...<br>'.mysql_error());
	//@mysql_select_db('datenbankname', $link)or die('Datenbank nicht erreichbar...<br>'.mysql_error());
	$link=$db;
	
	echo '<div id="map_de">';
	
	# Kartendatei setzen
	$map = new geoMap('img/map_de.gif');
	
	# Range einstellen
	//$map->m_setRange(5, 627,0, 840);
	//$map->m_setRange(15, 740, 10, 990);
	$map->m_setRange(0, 688, 0, 918);
	
	# Ort in Karte anzeigen lassen
	# Wenn der zweite Parameter auf 0 ist, wird die Eingabe 1:1 mit der Datenbank verglichen
	# Wenn der zweite Parameter auf 1 ist, werden alle Orte angezeigt, in denen die Eingabe enthalten ist
	# Beispiel:	$map->m_printPoints('hannover', 0);	-- Zeigt den Ort 'Hannover' auf der Karte
	#		$map->m_printPoints('han', 1);		-- Zeigt alle Orte in denen 'han' vorkommt
	$map->m_printPoints('Hannover', 0);
	//$map->m_printPoints('Blieskastel', 0);
	//$map->m_printPoints('Berlin', 0);
	//$map->m_printPoints('Rostock', 0);
	//$map->m_printPoints('Zeuthen', 0);
	//$map->m_printPoints('Augsburg', 0);
	
	echo '</div>';
?>