<?php
namespace bot;

class engine
{

	protected $conf;
	protected $user = [];
	protected $data = 
		[	'User' =>
			[
				'bd_id' => '',
				'id' => '',
				'first_name' => '',
				'usBackgroundMove' => '',
				'language_code' => '',
				'сity' => '',
			],
			'Message' => 
			[
				'type' => '',
				'chat_type' => '',
				'text' => '',
				'results' => '',
				'id' => '',
				'photo' => [],
			],
			'Answer' => 
			[
				'text' => '',
				'buttons' => '',
				'photo' => '',
			],
			'new_chat_participant' => [],
			'data' => 
			[
				'token' => '',
				'callback_query_id' => '',
			],
			'send' => [],
		];
	function __construct($mess)
	{  
		if(isset($mess['message']) and !isset($mess['message']['via_bot']) and !isset($mess['message']['photo']))
		{
			$this->data['Message']['type'] = 'message';
			$this->data['Message']['chat_type'] = $mess['message']['chat']['type'];
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['User']['id'] = $mess['message']['chat']['id'];
			if($this->data['Message']['chat_type'] != 'group')
			{
				$this->data['Message']['text'] = htmlspecialchars($mess['message']['text']);
				$this->data['User']['first_name'] = $mess['message']['chat']['first_name'];
			}else
			{
				$this->data['User']['first_name'] = $mess['message']['chat']['title'];
			}

		}elseif(isset($mess['inline_query']))
		{
			$this->data['Message']['type'] = 'inline_query';
			$this->data['Message']['chat_type'] = $mess['message']['chat']['type'];
			$this->data['Message']['id'] = $mess['inline_query']['id'];
			$this->data['Message']['text'] = htmlspecialchars($mess['inline_query']['query']);
			$this->data['User']['id'] = $mess['inline_query']['chat']['id'];
			$this->data['User']['first_name'] = $mess['inline_query']['chat']['first_name'];
		}elseif(isset($mess['message']['photo']))
		{	
			$this->data['Message']['type'] = 'photo';
			$this->data['Message']['chat_type'] = $mess['message']['chat']['type'];
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['Message']['text'] = htmlspecialchars('photo_18000');
			$this->data['Message']['photo'] = $mess['message']['photo'];
			$this->data['User']['id'] = $mess['message']['chat']['id'];
			$this->data['User']['first_name'] = $mess['message']['chat']['first_name'];
		}elseif(isset($mess['message']) and isset($mess['message']['via_bot']))
		{
			$this->data['Message']['type'] = 'inline_answer';
			$this->data['Message']['chat_type'] = $mess['message']['chat']['type'];
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['Message']['text'] = htmlspecialchars($mess['message']['text']);
			$this->data['User']['id'] = $mess['message']['chat']['id'];
			$this->data['User']['first_name'] = $mess['message']['chat']['first_name'];
		}elseif (isset($mess['callback_query'])) {
			$this->data['Message']['type'] = 'callback_query';
			$this->data['Message']['chat_type'] = $mess['callback_query']['message']['chat']['type'];
			$this->data['Message']['id'] = $mess['callback_query']['message']['message_id'];
			$this->data['Message']['text'] = $mess['callback_query']['data'];
			$this->data['data']['callback_query_id'] = $mess['callback_query']['id'];
			$this->data['User']['id'] = $mess['callback_query']['message']['chat']['id'];
			if($this->data['Message']['chat_type'] != 'group')
			{
				$this->data['User']['first_name'] = $mess['callback_query']['message']['chat']['first_name'];
			}else
			{
				$this->data['User']['first_name'] = $mess['callback_query']['message']['chat']['title'];
			}
		}else{die;}
		if(isset($mess['message']['new_chat_participant']))
		{
			$this->data['new_chat_participant'] = $mess['message']['new_chat_participant'];
		}
		$this->conf = include('admin/system/config.php');
		$db = mysqli_connect($this->conf['DB_host'],$this->conf['DB_log'],$this->conf['DB_pass'],$this->conf['DB_name']) or die('Ошибка подключения к БД');
			
		if($db)
		{

			mysqli_set_charset ( $db , 'utf8' ) or die('Не установлена кодировка');
			$usId = $this->data['User']['id'];
			$sql = "SELECT * FROM `users`  WHERE `telegram_id` = '$usId'";
			$us = mysqli_query($db,$sql);
			$user = mysqli_fetch_assoc($us);

			if($user)
			{
				$this->data['User']['bd_id'] = $user['id'];
				$sql = "UPDATE `users` SET `message_count` = `message_count` + 1 WHERE `users`.`id` = '". $user['id']."';";
				mysqli_query($db,$sql);
			}else
			{ 
				$usId = $this->data['User']['id'];
				$userName = $this->data['User']['first_name'];
				$sql = "INSERT INTO `users` (`id`, `name`, `phone`, `telegram_id`) VALUES (NULL, '$userName', '', '$usId');";
				mysqli_query($db,$sql);
				$this->data['User']['bd_id'] = mysqli_insert_id($db);
			}
		}

		
	}




	public function is_phone($_val)
	{
	    if (empty($_val)) {
	        return false;
	    }

	    if (!preg_match('/^\+?\d{10,15}$/', $_val)) {
	        return false;
	    }

	    if (
	        (mb_substr($_val, 0, 2) == '+7' and mb_strlen($_val) != 12) ||
	        (mb_substr($_val, 0, 1) == '7'  and mb_strlen($_val) != 11) ||
	        (mb_substr($_val, 0, 1) == '8'  and mb_strlen($_val) == 12) ||
	        (mb_substr($_val, 0, 1) == '9'  and mb_strlen($_val) == 10)
	    ) {
	        return false;
	    }
	    return true;
	}
	
	public function getGUID() {
	        $guid = '';
	        $namespace = rand(11111, 99999);
	        $uid = uniqid('', true);
	        $data = $namespace;
	        $data .= $_SERVER['REQUEST_TIME'];
	        $data .= $_SERVER['HTTP_USER_AGENT'];
	        $data .= $_SERVER['REMOTE_ADDR'];
	        $data .= $_SERVER['REMOTE_PORT'];
	        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	        $guid = substr($hash,  0,  8) . '-' .
	                substr($hash,  8,  4) . '-' .
	                substr($hash, 12,  4) . '-' .
	                substr($hash, 16,  4) . '-' .
	                substr($hash, 20, 12);
	        return $guid;
	    }

}
?>