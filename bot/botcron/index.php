<?php

class cronBot
{
	public $db;
	public $conf;
	public $load;
	public $mesSender;
	public function __construct($token)
	{
		if(empty($this->db))
		{
			include_once('../api/load.php');
			$this->load = new bot\api\load();
			include_once('../messSender.php');
			$this->mesSender = new bot\messSender();
			$this->conf = include_once('../admin/system/config.php');
			$this->db = mysqli_connect($this->conf['DB_host'],$this->conf['DB_log'],$this->conf['DB_pass'],$this->conf['DB_name']) or die('Ошибка подключения к БД');
		}
		$sql = "SELECT `cron_bot_token` FROM `system`";
        $token = mysqli_query($this->db,$sql);
        $token = mysqli_fetch_assoc($token);
        if($_POST['token'] == $token['cron_bot_token'])
        {
        	if(isset($_POST['route']))
        	{
				$route = explode('/', $_POST['route']);
				if (file_exists('modules/' . $route[0].'.php')) 
				{
					include_once 'modules/' . $route[0].'.php';
					$className = $route[0];
					$actionName = $route[1];
					$controllerName = new $className($this->db,$this->load,$this->mesSender);
					if(method_exists($controllerName,$actionName))
					{
						$controllerName->$actionName();
					}else{die('Action '.$actionName.' не найден');}
				}else{die('Файл '.$route[0].'.php контроллера '.$actionName.' не найден');}

        	}
        }
	}
}

if(isset($_POST['token']) and !empty($_POST['token']))
{
	new cronBot($_POST['token']);
}
?>