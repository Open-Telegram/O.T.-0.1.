<?php
/**
 * Created by VlA:D 06.05.2021
 */
class Controller
{	
	function __construct($route)
	{
		/**
		*------- МАРШРУТИЗАЦИЯ ------
		*/
		$route = explode('/', $route);
		foreach ($route as $key => $value) {
			$route[$key] = htmlspecialchars($value);
		}
		if(count($route) > 2)
		{
			die('не правильный маршрут');
		}
		if(empty($route[0])){$route[0] = 'home';}
		if(empty($route[1])){$route[1] = 'index';}



		if(!isset($_SESSION['token']) or empty($_SESSION['token']))
		{
			if(!empty($_POST) and !empty($_POST['login']) and !empty($_POST['pass']))
			{
				$_SESSION['log_login'] = htmlspecialchars($_POST['login']);
				$_SESSION['log_pass'] = htmlspecialchars($_POST['pass']);
				$_SESSION['log_route_0'] = htmlspecialchars($route[0]);
				$_SESSION['log_route_1'] = htmlspecialchars($route[1]);
			}
			$route[0] = 'users';
			$route[1] = 'login';

		}

		if (file_exists($GLOBALS['PTH'].'api/controller/'.$route[0].'.php')) {
				include_once $GLOBALS['PTH'].'api/controller/'.$route[0].'.php';
				$className = $route[0].'Controller';
				$actionName = $route[1];
				$controllerName = new $className;
				if(method_exists($controllerName,$actionName))
				{
					$controllerName->$actionName();
				}else{die('Action /api/controller/'.$actionName.' не найден');}
			}else{die('Файл '.$route[0].'.php контроллера /api/controller/'.$actionName.' не найден');}
	}
}
?>