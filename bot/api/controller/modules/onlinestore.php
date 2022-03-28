<?php
namespace bot\api\controller\modules;
include_once($_SERVER['DOCUMENT_ROOT'].'/bot/api/moduleController.php');
use bot\api\moduleController;
class onlinestoreController extends moduleController
{
	private $data;
	private $load;
	public $conf;
	private $command;
	function __construct($data=[],$conf=[],$command=[],$load=[])
	{
		$this->data = $data;
		$this->load = $load;
		$this->conf = $conf;
		$this->command = $command;
	}

	public function info()
	{
		$set_Text_send_menu = 'вставьте ссылку на ваш сайт c установленным модулем: <br><textarea name="text" ></textarea>';

		return [
			'name' => 'onlineStore',
			'version' => 1,
			'creator_name' => 'open telegram company',
			'information' => 'The module is an online store. Create a store right in the telegram',
			'functions' => ['send_menu','send_products'],
			'send_menu' => [
				'name' => 'Sending a menu',
				'info' => 'Отправляет меню. Это публичная кнопка.',
				'enter_html' => $set_Text_send_menu,
			],
			'send_products' => [
				'name' => 'Send products',
				'info' => 'Отправляет продукты пользователю, который выбрал категорию. Это кнопка не публичная. Дайте ей название "send_products" и не ставьте в бота.',
				'enter_html' => $set_Text_send_menu,
			]
		];
	}

	public function send_menu()
	{
		// $url = $this->command['return_data']['text'] . '/?route=api/opentelegram_products/products';
		$url = $this->command['return_data']['text'] . '?route=api/opentelegram_products/category&key=dhdfhbdhFDHFn4564';
		
		 
		// $headers = ['Content-Type: application/json']; // заголовки нашего запроса
		$ch = curl_init($url);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.7.62 Version/11.01');
		curl_setopt($ch, CURLOPT_HEADER, false);
		$json = curl_exec($ch);
		curl_close($ch);	
		if($json != '')
		{
			$json = json_decode($json,true);
		$this->data['Answer']['text'] = "Выберите категорию.";
		}else
		{
			$this->data['Answer']['text'] = "Произошла Ошибка";
		}
		if(isset($json['success']) and isset($json['success']['categories']))
		{
			$buttons = [];
			foreach ($json['success']['categories'] as $key => $value) 
			{
				$buttons[][] = ['text' => $value['name'], 'callback_data'=> 'send_products|'. $value['category_id']];
			}
			$this->data['Answer']['buttons'] = $this->getInlineKeyBoard($buttons);
		}else
		{
			$this->data['Answer']['text'] = "Произошла Ошибка";
		}
		return $this->data;
	}	

	public function send_products()
	{

		$text = explode('|',$this->data['Message']['text']);
		$id = end($text);
		$url = $this->command['return_data']['text'] . '?route=api/opentelegram_products/products&key=dhdfhbdhFDHFn4564&cat_id='.$id;
		
		 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.7.62 Version/11.01');
		curl_setopt($ch, CURLOPT_HEADER, false);
		$json = curl_exec($ch);
		curl_close($ch);
		if($json != '')
		{
			$json = json_decode($json,true);
			$this->data['Answer']['text'] = "Выберите товар.";
		}else
		{
			$this->data['Answer']['text'] = "Произошла Ошибка";
		}
		if(isset($json['success']) and isset($json['success']['products']))
		{
			$buttons = [];
			foreach ($json['success']['products'] as $key => $value) 
			{
				$buttons[][] = ['text' => $value['name'], 'url'=> $value['url']];
			}
			$this->data['Answer']['buttons'] = $this->getInlineKeyBoard($buttons);
		}else
		{
			$this->data['Answer']['text'] = "Товаров нет";
		}
		return $this->data;
	}
}