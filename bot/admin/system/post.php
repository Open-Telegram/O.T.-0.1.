<?php
if(isset($_POST))
{
	$retPost = [];
	$bossPost = $_POST;
	foreach($bossPost as $key => $post)
	{
		if(is_array($post))
		{
			$retPost[$key] = htmlArrayPostEncode($post);
		}else
		{
			$retPost[$key] = htmlspecialchars($post);
		}
	}
	return $retPost;
}else
{
	return [];
}
function htmlArrayPostEncode($post)
{
	$rettPost = [];
	foreach ($post as $key => $value) {
		if(is_array($value))
		{
			$rettPost[$key] = htmlArrayPostEncode($value);
		}else
		{
			$rettPost[$key] = htmlspecialchars($value);
		}
	}
	return $rettPost;
}
?>