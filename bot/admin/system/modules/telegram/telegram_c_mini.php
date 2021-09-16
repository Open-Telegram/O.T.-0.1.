<?php 
/**
 * 
 */
include_once $_SERVER['DOCUMENT_ROOT'].'/system/modules/telegram/telegram_c.php';
class telegrammSenderMini extends telegrammSender
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
	public function __construct($telegram_id)
	{
		$this->data['User']['id'] = $telegram_id;
	}
	public function send_mini_m($message,$telegram_id='')
	{
		if(!empty($telegram_id))
		{
			$this->data['User']['id'] = $telegram_id;
		}
		if(!empty($message))
		{
			$this->data['Answer']['text'] = $message;
		}
		$this->sendMessage('sendMessage',$this->data);
	}
}
 ?>