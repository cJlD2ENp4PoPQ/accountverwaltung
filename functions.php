<?php
function SecureValue($value)
{
	
	$value = htmlspecialchars(stripslashes($value), ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
    $value = mysql_real_escape_string($value);	
	
	return ($value);
}

function doPost($uri,$postdata,$host){

	//error_reporting(E_ALL);
  $ch = curl_init("http://$host/$uri?$postdata");
  //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  //curl_setopt($ch, CURLOPT_POSTREDIR, 3);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $response=curl_exec($ch);
  
  //echo curl_error($ch);

	return $response;
	
	//echo $response.'<br>';

/*
	$response='';
	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$out  ="POST $uri?$postdata HTTP/1.1\r\n";
		$out .="Host: $host\r\n";
		$out .="Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		while (!feof($fp)) {
			$response.=fgets($fp, 128);
		}
		fclose($fp);
	}
	echo $response.'<br>';
	
	//erste zeile entfernen
	$response=explode("\r\n\r\n",$response);
    $header=$response[0];
    $responsecontent=$response[1];	

	return $responsecontent;
*/
/*
  $da = fsockopen($host, 80, $errno, $errstr);
  
  if (!$da)
  {
    echo "$errstr ($errno)<br/>\n";
    echo $da;
  }
  else
  {
    $salida ="POST $uri  HTTP/1.1\r\n";
    $salida.="Host: $host\r\n";
    $salida.="User-Agent: PHP Script\r\n";
    $salida.="Content-Type: application/x-www-form-urlencoded\r\n";
    $salida.="Content-Length: ".strlen($postdata)."\r\n";
    $salida.="Connection: close\r\n\r\n";
    $salida.=$postdata;
	var_dump($salida);
    fwrite($da, $salida);
    while (!feof($da))
      $response.=fgets($da, 128);
    $response=explode("\r\n\r\n",$response);
    $header=$response[0];
    $responsecontent=$response[1];
	
    if(!(strpos($header,"Transfer-Encoding: chunked")===false))
    {
      $aux=explode("\r\n",$responsecontent);
      for($i=0;$i<count($aux);$i++)
        if($i==0 || ($i%2==0))$aux[$i]="";
      $responsecontent=implode("",$aux);
    }//if
    return chop($responsecontent);
  }//else

 */
}//function-doPost
?>
