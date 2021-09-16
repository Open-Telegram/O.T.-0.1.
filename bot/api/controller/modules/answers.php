<?php
namespace bot\api\controller\modules;
include_once($_SERVER['DOCUMENT_ROOT'].'/bot/api/moduleController.php');
use bot\api\moduleController;
class answersController extends moduleController
{
	private $data;
	private $load;
	private $conf;
	private $command;
	function __construct($data=[],$conf=[],$command=[],$load=[])
	{
		$this->data = $data;
		$this->load = $load;
		$this->conf = $conf;
		$this->command = $command;
	}

	public function install()
	{
		/*Тут то, что должно исполниться при установке модуля*/
	}

	public function info()
	{

		$set_Text_Answer_By_Keyboard_Buttons = 'Enter the response to the user: <br><textarea name="text" ></textarea><hr>Select buttons: <br><select multiple name="buttons"></select>';
		$set_Text_Answer_By_Inline_Buttons = 'Enter the response to the user: <br><textarea name="text" ></textarea><hr>Select buttons: <br><select multiple name="buttons"></select>';
		$set_Just_Text_Answer = 'Enter the response to the user: <br><textarea name="text" ></textarea>';
		return [
			'name' => 'answers',//название и ид модуля, не должны пересекаться с другими модулями
			'version' => 1,//версия
			'creator_name' => 'open telegram company',
			'information' => 'A standard module for creating text responses with buttons.',//описание модуля
			'functions' => ['set_Text_Answer_By_Keyboard_Buttons','set_Text_Answer_By_Inline_Buttons','set_Just_Text_Answer'],//Какие функции существуют у этого модуля.  не должны пересекаться с другими модулями
			'set_Text_Answer_By_Keyboard_Buttons' => [
				'name' => 'Answer with keyboard buttons and text',
				'info' => 'Sets the response using keyboard buttons or text',//Описание функц можно и html
				'enter_html' => $set_Text_Answer_By_Keyboard_Buttons,//это html ответа функции. То есть то, что пользовательно должен выбрать или ввести. Это данные, которая бункция должна обрабатывать и в связи с нми принимать решения.Например эта функция принимает инпут текст и селект кнопок.
			],
			'set_Text_Answer_By_Inline_Buttons' => [
				'name' => 'Answer with the buttons under the text and the text',
				'info' => 'Sets the response using the buttons under the text or text',
				'enter_html' => $set_Text_Answer_By_Inline_Buttons,
			],
			'set_Just_Text_Answer' => [
				'name' => 'Text response',
				'info' => 'Sets the response only in text',
				'enter_html' => $set_Just_Text_Answer,
			],
		];
	}

	/*************************************************************************************
	Сама функция, которая будет срабатывать, если на неё укажет админ, при комманде или кнопке.
	Она должна принимать в себя значение, которое вводит пользователь (либо то что выбрал админ. функционально.) и отдавать то, что указано в info, то, что выбрал админ.
	И, конечно, выполнять работу, предназначеную для неё.
	Данная функция setTextAnswer - стандартная и отдаёт пользователю в бота выбранные админом ответы.
	*************************************************************************************/
	public function set_Text_Answer_By_Keyboard_Buttons()
	{

		$this->data['Answer']['text'] = $this->command['return_data']['text'];
		if(isset($this->command['return_data']['buttons']) and !empty($this->command['return_data']['buttons']))
		{

			$modelButtons = $this->load->load('model_buttons');
			$buttons = [];
			foreach ($this->command['return_data']['buttons'] as $key => $button) 
			{
				$buttonArr = $modelButtons->getButton($button);
				$buttons[][] = ['text' => $buttonArr['button_name']];
			}
			$this->data['Answer']['buttons'] = $this->getKeyBoard($buttons);
		}
		return $this->data;
	}

	public function set_Text_Answer_By_Inline_Buttons()
	{

		$this->data['Answer']['text'] = $this->command['return_data']['text'];
		if(isset($this->command['return_data']['buttons']) and !empty($this->command['return_data']['buttons']))
		{

			$modelButtons = $this->load->load('model_buttons');
			$buttons = [];
			foreach ($this->command['return_data']['buttons'] as $key => $button) 
			{
				$buttonArr = $modelButtons->getButton($button);
				$buttons[][] = ['text' => $buttonArr['button_name'], 'callback_data'=> $buttonArr['button_name']];
			}

			$this->data['Answer']['buttons'] = $this->getInlineKeyBoard($buttons);
		}
		return $this->data;
	}

	public function set_Just_Text_Answer()
	{
		$this->data['Answer']['text'] = $this->command['return_data']['text'];
		return $this->data;
	}
} 
?>