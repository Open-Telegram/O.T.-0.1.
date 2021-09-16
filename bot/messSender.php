<?php

namespace bot;

class messSender
{
	public function sendMessage($action,$data)//sendMessage , editMessageText , editMessageReplyMarkup, answerInlineQuery,editMessageText,deleteMessage,sendPhoto,answerCallbackQuery
	{
		switch ($action) {
			case 'sendMessage':
				$response = array(
						'chat_id' => $data['User']['id'],
						'text' =>  $data['Answer']['text'],
						'parse_mode' => 'HTML'
					);
				break;
			case 'answerInlineQuery':
				$response = array(
						'inline_query_id' => $data['Message']['id'],
						'results' =>  $data['Message']['results'],
						'cache_time' => '2',
					);
				break;
			case 'deleteMessage':
				$response = array(
						'chat_id' => $data['User']['id'],
						'message_id' =>  $this->data['Message']['id']
					);
				break;
			case 'sendPhoto':
				$response = array(
						'chat_id' => $data['User']['id'],
						'photo' =>  $this->conf['photo_url'] .$this->data['Answer']['photo'],
						'caption' => $data['Answer']['text'],
					);
				break;
			case 'editMessageText':
				$response = array(
						'chat_id' => $data['User']['id'],
						'message_id' =>  $data['Message']['id'],
						'text' =>  $data['Answer']['text'],
						'parse_mode' => 'HTML'
					);
				break;
			case 'editMessageReplyMarkup':
				$response = array(
						'chat_id' => $data['User']['id'],
						'message_id' =>  $data['Message']['id'],
					);
				break;
			case 'answerCallbackQuery':
				$response = array(
						'callback_query_id' => $data['data']['callback_query_id'],
						'text' =>  $data['Answer']['text'],
						'show_alert' => true,
					);
				break;
			case 'kickChatMember':
				$response = array(
						'chat_id' => $data['User']['chat_id'],
						'user_id' => $data['User']['id'],
					);
				break;
			default:
				break;
		}

		if(!empty($data['Answer']['buttons']))
		{
			$response['reply_markup'] = $data['Answer']['buttons'];
		}
		if(!isset($this->conf))
		{
			$conf = include($_SERVER['DOCUMENT_ROOT'] . '/bot/admin/system/config.php');
			$this->send($response,$action,$conf['Telegram_api']);
		}else
		{
			$this->send($response,$action,$this->conf['Telegram_api']);
		}
	}

	public function sendMessForAdm($telegram_id,$message,$api)
	{
		$response = array(
				'chat_id' => $telegram_id,
				'text' =>'Message from the Administrator: ' .PHP_EOL. $message,
				'parse_mode' => 'HTML'
			);
		$this->send($response,'sendMessage',$api);
	}

	private function send($response,$action,$api)
	{
		$ch = curl_init('https://api.telegram.org/bot' . $api . '/'. $action);  
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_exec($ch);
		curl_close($ch);
	}
	
}
?>