<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
ini_set('log_errors', 'On');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/php_errors.log');


include_once($_SERVER['DOCUMENT_ROOT'] . '/bot/messHandler.php');
$Telegram_api = include($_SERVER['DOCUMENT_ROOT'] . '/bot/admin/system/config.php');
use bot\messHandler;
if(isset($_GET['id']) and $_GET['id']== $Telegram_api['Telegram_api'])
{
	unset($Telegram_api);
	$data = file_get_contents('php://input');
	if(isset($data))
	{
		$data = json_decode($data, true);
		$bot = new messHandler($data);
	}

}else
{
	echo 1234;
//	header('Location: https://'.$_SERVER['HTTP_HOST'].'/bot/admin', true, 301);


//    $Telegram_api = include($_SERVER['DOCUMENT_ROOT'] . '/bot/admin/system/config.php');
	$botAPI = '1921363334:AAGjr5eLI_FM5E9TTt36V1lGuuSIhEBmrjg';
//	$botAPI = '1921363334:AAEV4BZnxGaONHNYYWi6izCa7DV-B7xXH4Y';//Удалить эту строку после подключения бота.
	// $url = "https://trahbot.vlad-egorov.ru/?id=$botAPI";//И эту
	// echo file_get_contents("https://api.telegram.org/bot$botAPI/setWebhook?url=$url"); //Замены переменных обновить страницу в браузере
	// если в браузер вываелась строка {"ok":true,"result":true,"description":"Webhook was set"} - значит бот успешно подключён и можно прступать ко 2-ой части установки.


	echo file_get_contents("https://api.telegram.org/bot1921363334:AAGjr5eLI_FM5E9TTt36V1lGuuSIhEBmrjg/getWebhookInfo"); //Для удаления прошлого адресса | deleteWebhook | getWebhookInfo
}


?>