<?php
/**
 * Created by VlA:D 06.05.2021
 */
class RouteController
{
	
	function __construct()
	{
		$GLOBALS['PATH_IMAGES'] = $_SERVER['DOCUMENT_ROOT'] . '/bot/admin/src/img/';
		$GLOBALS['URL_IMAGES'] = 'https://' . $_SERVER['SERVER_NAME'] . '/bot/admin/src/img/';
		$GLOBALS['PTH'] = $_SERVER['DOCUMENT_ROOT'] .'/bot/admin/';
		$GLOBALS['BOT_BOTCRON_MODULES'] = $_SERVER['DOCUMENT_ROOT'] .'/bot/botcron/modules/';
		$GLOBALS['BOT_MODEL_MODULES'] = $_SERVER['DOCUMENT_ROOT'] .'/bot/api/model/modules/';
		$GLOBALS['BOT_CONTROLLER_MODULES'] = $_SERVER['DOCUMENT_ROOT'] .'/bot/api/controller/modules/';
		if(isset($_GET['route']) and !empty($_GET['route']))
		{
			$route = $_GET['route'];
		}else
		{
			$route = 'home/index';
		}
		
		session_start();
		include_once ($GLOBALS['PTH'].'system/Controller.php');
		new Controller($route);
	}

}
?>