<?php
namespace bot\api;
/**
 * 
 */
class model
{
	public $db;
	public $conf;
	function __construct()
	{
		$this->conf = include($_SERVER['DOCUMENT_ROOT'] . '/bot/admin/system/config.php');
		$db = mysqli_connect($this->conf['DB_host'],$this->conf['DB_log'],$this->conf['DB_pass'],$this->conf['DB_name']) or die('Ошибка подключения к БД');
			
		if($db)
		{
			mysqli_set_charset ( $db , 'utf8' ) or die('Не установлена кодировка');
			$this->db = $db;
		}
	}

	function debug($data)
	{
		ob_start();
		print_r($data);
		$out = ob_get_clean(); 
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/messageDEB.txt', $out, FILE_APPEND); 
	}
}
?>