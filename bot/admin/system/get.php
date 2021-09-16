<?php
if(isset($_GET))
{
	$retGet = [];
	$bossGet = $_GET;
	foreach($bossGet as $key => $get)
	{
		if(is_array($get))
		{
			$retGet[$key] = htmlArrayGetDecode($get);
		}else
		{
			$retGet[$key] = htmlspecialchars($get);
		}
	}
	
	return $retGet;
}else
{
	return [];
}
function htmlArrayGetDecode($get)
{
	$rettGet = [];
	foreach ($get as $key => $value) {
		if(is_array($value))
		{
			$rettGet[$key] = htmlArrayGetDecode($value);
		}else
		{
			$rettGet[$key] = htmlspecialchars($value);
		}
	}
	return $rettGet;
}
?>