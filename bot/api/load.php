<?php
namespace bot\api;
/**
 * 
 */
class load
{
	function load($patht,$data=[])
	{
		$pp = $patht;
		$path = explode('_', $patht);
		$last = (count($path)-1);
		$url = $_SERVER['DOCUMENT_ROOT'] . '/bot/api/';
		$class = 'bot\api\\';
		foreach($path as $k=>$p)
		{
			if($k == $last)
			{
				$class .= $p;
				$url .= $p . '.php';
			}else
			{
				$class .= $p . '\\';
				$url .= $p . '/';
			}
		}
		if($inc = include_once($url))
		{
			$class = new $class;
			return $class;
		}else
		{
			$this->error($inc);
		};
	}

	function error($data)
	{
		ob_start();
		print_r($data);
		$out = ob_get_clean(); 
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/ERROR.txt', $out, FILE_APPEND);
		die;
	}
}
?>