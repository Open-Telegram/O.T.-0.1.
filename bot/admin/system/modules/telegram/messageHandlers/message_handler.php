<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/system/modules/telegram/engine.php';

class messageHandler extends Engine
{
	function __construct($data)
	{	
		parent::__construct($data);
		$this->messageHand();
		//$this->debugT($this->data);
	}
	
	private function messageHand()
	{
		switch($this->data['Message']['text'])
		{
			case 'В начало':
			case '/start':
				$this->data['Answer']['text'] = 'Я ваша личная напоминалка о задачах. Прикрепите меня к своему аккаунту и увидите чууууууудоооооо';
				$inlineButtons[][] = ['text' => 'Прикрепить аккаунт к этому телеграм'];
			    $this->data['Answer']['buttons'] = $this->getKeyBoard($inlineButtons);
				$this->sendM();
				break;
			case 'Прикрепить аккаунт к этому телеграм':
				$this->data['Answer']['text'] = 'Ваш личный ид доступа:';
				$this->sendM();
				$this->data['Answer']['text'] = $this->data['User']['id'];
				$this->sendM();
				break;
			default:
				$this->data['Answer']['text'] = 'нуууу.... я не знаю что и ответить';
				$inlineButtons[][] = ['text' => 'В начало'];
			    $this->data['Answer']['buttons'] = $this->getKeyBoard($inlineButtons);
				$this->sendM();
				break;
		}
	}
	
}
?>