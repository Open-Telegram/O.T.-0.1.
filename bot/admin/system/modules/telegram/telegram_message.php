<?php 	
class telegramMessage
{
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
			'data' => 
			[
				'token' => '',
				'callback_query_id' => '',
			],
		];
	function __construct($mess)
	{
		if(isset($mess['message']) and !isset($mess['message']['via_bot']) and !isset($mess['message']['photo']))
		{
			$this->data['Message']['type'] = 'message';
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['Message']['text'] = htmlspecialchars($mess['message']['text']);
			$this->data['User']['id'] = $mess['message']['from']['id'];
			$this->data['User']['first_name'] = $mess['message']['from']['first_name'];

		}elseif(isset($mess['inline_query']))
		{
			$this->data['Message']['type'] = 'inline_query';
			$this->data['Message']['id'] = $mess['inline_query']['id'];
			$this->data['Message']['text'] = htmlspecialchars($mess['inline_query']['query']);
			$this->data['User']['id'] = $mess['inline_query']['from']['id'];
			$this->data['User']['first_name'] = $mess['inline_query']['from']['first_name'];
		}elseif(isset($mess['message']['photo']))
		{	
			$this->data['Message']['type'] = 'photo';
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['Message']['text'] = htmlspecialchars('photo_18000');
			$this->data['Message']['photo'] = $mess['message']['photo'];
			$this->data['User']['id'] = $mess['message']['from']['id'];
			$this->data['User']['first_name'] = $mess['message']['from']['first_name'];
		}elseif(isset($mess['message']) and isset($mess['message']['via_bot']))
		{
			$this->data['Message']['type'] = 'inline_answer';
			$this->data['Message']['id'] = $mess['message']['message_id'];
			$this->data['Message']['text'] = htmlspecialchars($mess['message']['text']);
			$this->data['User']['id'] = $mess['message']['from']['id'];
			$this->data['User']['first_name'] = $mess['message']['from']['first_name'];
		}elseif (isset($mess['callback_query'])) {
			//$this->debWrite($mess);
			$this->data['Message']['type'] = 'callback_query';
			$this->data['Message']['id'] = $mess['callback_query']['message']['message_id'];
			$this->data['Message']['text'] = $mess['callback_query']['data'];
			$this->data['data']['callback_query_id'] = $mess['callback_query']['id'];
			$this->data['User']['id'] = $mess['callback_query']['message']['chat']['id'];
			$this->data['User']['first_name'] = $mess['callback_query']['message']['chat']['first_name'];
		}else{die;}
		switch ($this->data['Message']['type']) 
		{
			case 'message':
				include('messageHandlers/message_handler.php');
				new messageHandler($this->data);
				break;
			case 'callback_query':
				include('messageHandlers/callback_query_handler.php');
				new callbackQueryHandler($this->data);
				break;
			case 'inline_query':
				break;
			case 'inline_answer':
				break;
			default:
				break;
		}
	}
	
	
}
	
?>