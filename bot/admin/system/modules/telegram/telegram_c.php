<?php 
/**
 * Created by VlAD for Cigitus company 17.12.2020
 */
class telegrammSender
{
	private $api = '1065375267:AAEegzOGJiu0i88a3Ko4pR9U9nAICiR0WOA';

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
			default:
				break;
		}
		if(!empty($data['Answer']['buttons']))
		{
			$response['reply_markup'] = $data['Answer']['buttons'];
		}
		$this->send($response,$action);
	}
	private function send($response,$action)
	{
		$ch = curl_init('https://api.telegram.org/bot' . $this->api . '/'. $action);  
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_exec($ch);
		curl_close($ch);
	}
}
 ?>