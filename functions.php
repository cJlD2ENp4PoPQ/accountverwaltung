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
}	

function utf8_encode_fix($string)
{
    return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
}

function utf8_decode_fix($string)
{
    return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
}
